<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserTable extends SearchableTable
{
    public string $role = '';
    public array $roles = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->roles = Role::pluck('name')->toArray();
    }

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

        $query->when($this->role !== '', fn ($q, $role) => $q->whereHas('roles', fn ($roleQuery) => $roleQuery->where('name', $role)));

        return $query->paginate($this->perPage);
    }
}
