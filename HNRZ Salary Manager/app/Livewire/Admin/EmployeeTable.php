<?php

namespace App\Livewire\Admin;

use App\Models\Employee;

class EmployeeTable extends SearchableTable
{
    protected function getView(): string
    {
        return 'livewire.admin.employee-table';
    }

    public function getItems()
    {
        $query = Employee::query()
            ->with(['position', 'payrollMethod'])
            ->orderByDesc('created_at');

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('id_pekerja', 'like', $search)
                    ->orWhere('nik', 'like', $search)
                    ->orWhere('nama_lengkap', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('jabatan', 'like', $search)
                    ->orWhere('role', 'like', $search)
                    ->orWhere('no_telepon', 'like', $search);
            });
        }

        return $query->paginate($this->perPage);
    }
}
