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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:20'],
        ]);

        $query = Employee::query()
            ->with(['position', 'payrollMethod'])
            ->when($validated['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('id_pekerja', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('no_telepon', 'like', "%{$search}%");
                });
            })
            ->when($validated['jabatan'] ?? null, function ($q, $jabatan) {
                $jabatan = trim((string) $jabatan);
                $q->where(function ($sub) use ($jabatan) {
                    $sub->where('jabatan', $jabatan)
                        ->orWhereHas('position', fn($positionQuery) => $positionQuery->where('name', $jabatan));
                });
            })
            ->when(($validated['status'] ?? null) !== null, function ($q, $status) {
                $q->where('is_active', in_array(Str::lower(trim((string) $status)), ['aktif', '1', 'true', 'yes', 'on'], true));
            })
            ->when($validated['role'] ?? null, function ($q, $role) {
                $q->whereRaw('LOWER(role) = ?', [Str::lower(trim((string) $role))]);
            });

        $employees = $query->orderBy('created_at', 'desc')->paginate(5)->appends($request->query());
        $roles = \Spatie\Permission\Models\Role::pluck('name')->toArray();
        $jabatans = Jabatan::orderBy('name')->pluck('name')->toArray();

        return view('admin.employees.index', compact('employees', 'roles', 'jabatans'));
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

        return DB::transaction(function () use ($validated, $request) {
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
                'is_active' => $request->boolean('is_active', true),
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

        $request->validate([
            'bonus_tetap_ids' => 'nullable|array',
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
                'is_active' => $request->boolean('is_active', true),
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
            ->with('success', 'Data karyawan berhasil dipindahkan ke Recycle Bin.');
    }

    /**
     * Tampilkan daftar karyawan yang berada di Recycle Bin.
     */
    public function trash(Request $request)
    {
        $query = Employee::onlyTrashed()->with(['position', 'payrollMethod']);

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

        $employees = $query->orderByDesc('deleted_at')->paginate(5);

        return view('admin.employees.trash', compact('employees'));
    }

    /**
     * Kembalikan karyawan (beserta akun user terkait) dari Recycle Bin.
     */
    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($employee) {
            $employee->restore();

            $user = User::onlyTrashed()->where('id', $employee->user_id)->first();
            if ($user) {
                $user->restore();
            }
        });

        return redirect()->route('admin.employees.trash')
            ->with('success', "Karyawan '{$employee->nama_lengkap}' berhasil dipulihkan.");
    }

    /**
     * Hapus karyawan (beserta akun user terkait) secara permanen dari Recycle Bin.
     */
    public function forceDelete($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $nama = $employee->nama_lengkap;
        $userId = $employee->user_id;

        DB::transaction(function () use ($employee, $userId) {
            // Hapus employee terlebih dahulu agar tidak melanggar foreign key ke users.
            $employee->forceDelete();

            $user = User::onlyTrashed()->where('id', $userId)->first();
            if ($user) {
                $user->forceDelete();
            }
        });

        return redirect()->route('admin.employees.trash')
            ->with('success', "Karyawan '{$nama}' berhasil dihapus permanen.");
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
