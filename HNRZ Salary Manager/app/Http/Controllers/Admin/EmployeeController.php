<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Bonus;
use App\Models\Employee;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query()->with(['position', 'payrollMethod']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_pekerja', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $jabatans = Jabatan::orderBy('name')->get();

        return view('admin.employees.create', compact('jabatans'));
    }

    public function store(EmployeeStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['id_pekerja'] = $this->generateEmployeeId();

        return DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->syncRoles([$validated['role']]);

            $job = Jabatan::where('name', $validated['jabatan'])->first();

            $employee = Employee::create([
                'user_id' => $user->id,
                'id_pekerja' => $validated['id_pekerja'],
                'nik' => $validated['nik'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_telepon' => $validated['no_telepon'],
                'email' => $validated['email'],
                'alamat' => $validated['alamat'],
                'jabatan' => $validated['jabatan'],
                'jabatan_id' => $job?->id,
                'role' => $validated['role'],
            ]);

            if (! $employee->exists) {
                throw new \Exception('Failed to create employee');
            }

            return redirect()->route('admin.employees.index')
                ->with('success', 'Data karyawan berhasil ditambahkan.');
        });
    }

    public function show(Employee $employee)
    {
        $employee->load(['position', 'payrollMethod', 'bonuses']);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $jabatans = Jabatan::orderBy('name')->get();
        $variableBonuses = Bonus::where('jenis_bonus', 'Variabel')->orderBy('nama_bonus')->get();
        $currentBonusVariabelId = $employee->bonuses()->where('jenis_bonus', 'Variabel')->value('bonuses.id');

        $tetapBonuses = Bonus::where('jenis_bonus', 'Tetap')->orderBy('nama_bonus')->get();
        $currentTetapBonusIds = $employee->bonuses()
            ->where('jenis_bonus', 'Tetap')
            ->pluck('bonuses.id')
            ->toArray();

        return view('admin.employees.edit', compact(
            'employee', 'jabatans', 'variableBonuses', 'currentBonusVariabelId',
            'tetapBonuses', 'currentTetapBonusIds'
        ));
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        // Validasi tambahan untuk pilihan bonus tetap (dicentang = tetap diberikan, tidak dicentang = dibatalkan)
        $request->validate([
            'bonus_tetap_ids'   => 'nullable|array',
            'bonus_tetap_ids.*' => 'exists:bonuses,id',
        ]);

        return DB::transaction(function () use ($employee, $validated, $request) {
            $job = Jabatan::where('name', $validated['jabatan'])->first();

            $employee->update([
                'nik' => $validated['nik'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_telepon' => $validated['no_telepon'],
                'email' => $validated['email'],
                'alamat' => $validated['alamat'],
                'jabatan' => $validated['jabatan'],
                'jabatan_id' => $job?->id,
                'role' => $validated['role'],
            ]);

            $user = $employee->user;

            if ($user) {
                $userData = [
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                ];

                if (! empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

            $user->update($userData);
                $user->syncRoles([$validated['role']]);
            }

            // Bonus tetap sekarang mengikuti pilihan admin di form (checklist),
            // bukan otomatis dipertahankan semua seperti sebelumnya
            $syncIds = $request->input('bonus_tetap_ids', []);

            if (! empty($validated['bonus_variabel_id'])) {
                $syncIds[] = $validated['bonus_variabel_id'];
            }

            $employee->bonuses()->sync($syncIds);

            return redirect()->route('admin.employees.index')
                ->with('success', 'Data karyawan berhasil diperbarui.');
        });
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            $user = $employee->user;
            $employee->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }

    private function generateEmployeeId(): string
    {
        do {
            $lastEmployee = Employee::query()->orderByDesc('id')->first();

            $number = 1;
            if ($lastEmployee && preg_match('/^PKR(\d+)$/', $lastEmployee->id_pekerja, $matches)) {
                $number = (int) $matches[1] + 1;
            }

            $candidate = 'PKR' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } while (Employee::where('id_pekerja', $candidate)->exists());

        return $candidate;
    }
}
