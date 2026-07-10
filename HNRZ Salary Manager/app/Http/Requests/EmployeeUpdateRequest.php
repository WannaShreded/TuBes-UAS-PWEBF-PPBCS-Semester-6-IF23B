<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $employee = $this->route('employee');
        $employeeId = $employee?->id;
        $userId = $employee?->user?->id;

        return [
            'nik' => ['required', 'string', 'max:50', 'unique:employees,nik,' . $employeeId],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:employees,email,' . $employeeId,
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'alamat' => ['required', 'string'],
            'jabatan' => ['required', 'string', 'max:100'],
            'role' => ['required', 'in:admin,karyawan'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'bonus_variabel_id' => ['nullable', 'exists:bonuses,id'],
        ];
    }
}
