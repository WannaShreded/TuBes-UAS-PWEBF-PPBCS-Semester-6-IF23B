<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    private function generateEmployeeId(): string
    {
        do {

            $lastEmployee = Employee::orderByDesc('id')->first();

            $number = 1;

            if (
                $lastEmployee &&
                preg_match('/^PKR(\d+)$/', $lastEmployee->id_pekerja, $matches)
            ) {

                $number = (int)$matches[1] + 1;
            }

            $candidate = 'PKR' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } while (Employee::where('id_pekerja', $candidate)->exists());

        return $candidate;
    }

    public function index()
    {
        $employees = \App\Models\Employee::with('position')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diambil',
            'data' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:employees',
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:users|unique:employees',
            'alamat' => 'required|string',
            'jabatan' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|min:8',
            'is_active' => 'boolean',
        ]);

        return DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($validated['role']);

            $jabatan = Jabatan::where('name', $validated['jabatan'])->first();

            $employee = Employee::create([
                'user_id' => $user->id,
                'id_pekerja' => $this->generateEmployeeId(),
                'nik' => $validated['nik'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_telepon' => $validated['no_telepon'],
                'email' => $validated['email'],
                'alamat' => $validated['alamat'],
                'jabatan' => $validated['jabatan'],
                'jabatan_id' => $jabatan?->id,
                'role' => $validated['role'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee berhasil ditambahkan',
                'data' => $employee,
            ], 201);
        });
    }

    public function show($id) {}

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nik' => 'required|string|max:20|unique:employees,nik,' . $employee->id,
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'alamat' => 'required|string',
            'jabatan' => 'required|string',
            'role' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $employee->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $employee,
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee tidak ditemukan'
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil dihapus',
        ]);
    }
}
