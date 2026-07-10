<?php

namespace App\Livewire\Admin;

use App\Models\User;

class UserTable extends SearchableTable
{
    protected function getView(): string
    {
        return 'livewire.admin.user-table';
    }

    public function getItems()
    {
        $query = User::query()->with('roles')->orderBy('name');

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', $search);
                    });
            });
        }

        return $query->paginate($this->perPage);
    }
}
