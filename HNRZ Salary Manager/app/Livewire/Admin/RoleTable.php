<?php

namespace App\Livewire\Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTable extends SearchableTable
{
    public string $permission = '';
    public array $permissions = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'permission' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->permissions = Permission::pluck('name')->toArray();
    }

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

        $query->when($this->permission !== '', fn ($q, $permission) => $q->whereHas('permissions', fn ($permissionQuery) => $permissionQuery->where('name', 'like', "%{$permission}%")));

        return $query->paginate($this->perPage);
    }
}
