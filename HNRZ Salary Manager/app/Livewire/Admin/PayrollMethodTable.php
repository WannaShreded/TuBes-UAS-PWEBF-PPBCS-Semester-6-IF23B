<?php

namespace App\Livewire\Admin;

use App\Models\PayrollMethod;

class PayrollMethodTable extends SearchableTable
{
    protected function getView(): string
    {
        return 'livewire.admin.payroll-method-table';
    }

    public function getItems()
    {
        $query = PayrollMethod::query()->latest();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('type', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        return $query->paginate($this->perPage);
    }
}
