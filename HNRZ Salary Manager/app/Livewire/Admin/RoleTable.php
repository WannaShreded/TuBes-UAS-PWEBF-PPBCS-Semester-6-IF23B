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

    protected $listeners = [
        'call-livewire-action' => 'handleAction',
    ];

    public function handleAction(string $action, array $params): void
    {
        if (method_exists($this, $action)) {
            $this->$action(...$params);
        }
    }

    public function confirmDelete(int $id, string $name): void
    {
        // Dispatch event ke Alpine.js di luar Livewire
        $this->dispatch('open-confirm-modal', [
            'type'        => 'danger',
            'title'       => 'Konfirmasi Hapus',
            'message'     => "Anda akan menghapus role \"{$name}\". Tindakan ini tidak dapat dibatalkan.",
            'confirmText' => 'Ya, Hapus',
            'roleId'      => $id,
        ]);
    }

    public function deleteRole(int $id): void
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            $this->dispatch('notify',
                message: 'Role tidak dapat dihapus karena masih digunakan oleh ' . $role->users()->count() . ' user.',
                type: 'error'
            );
            return;
        }

        $role->delete();
        $this->dispatch('notify', message: 'Role berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        return view($this->getView(), [
            'items' => $this->getItems(),
        ]);
    }
}
