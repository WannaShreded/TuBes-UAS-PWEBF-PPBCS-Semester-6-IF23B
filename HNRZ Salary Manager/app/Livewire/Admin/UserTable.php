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

    protected $listeners = ['call-livewire-action' => 'handleAction'];

    public function handleAction(string $action, array $params): void
    {
        if (method_exists($this, $action)) {
            $this->$action(...$params);
        }
    }

    public function confirmDelete(int $id, string $name): void
    {
        $this->dispatch('open-confirm-modal', [
            'type'           => 'danger',
            'title'          => 'Konfirmasi Hapus',
            'message'        => "Anda akan menghapus user \"{$name}\". Tindakan ini tidak dapat dibatalkan.",
            'confirmText'    => 'Ya, Hapus',
            'livewireAction' => 'deleteItem',
            'roleId'         => $id,
        ]);
    }

    public function deleteItem(int $id): void
    {
        User::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'User berhasil dihapus.', type: 'success');
    }
}
