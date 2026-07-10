<?php

namespace App\Livewire\Admin;

use Spatie\Permission\Models\Role;

class RoleTable extends SearchableTable
{
    protected function getView(): string
    {
        return 'livewire.admin.role-table';
    }

    public function getItems()
    {
        $query = Role::query()->withCount('permissions')->orderBy('name');

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search);
            });
        }

        return $query->paginate($this->perPage);
    }
}
