<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'max:50', 'unique:employees,nik'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email', 'unique:users,email'],
            'alamat' => ['required', 'string'],
            'jabatan' => ['required', 'string', 'max:100'],
            'role' => ['required', 'in:admin,karyawan'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
