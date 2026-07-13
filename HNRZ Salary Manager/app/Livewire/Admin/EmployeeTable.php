<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\Jabatan;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class EmployeeTable extends SearchableTable
{
    public string $sortField = 'created_at';
    public string $role = '';
    public string $jabatan = '';
    public string $status = '';
    public array $roles = [];
    public array $jabatans = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
        'jabatan' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->roles = Role::pluck('name')->toArray();
        $this->jabatans = Jabatan::orderBy('name')->pluck('name')->toArray();
    }

    protected function getView(): string
    {
        return 'livewire.admin.employee-table';
    }

    public function getItems()
    {
        $query = Employee::query()
            ->with(['position', 'payrollMethod']);

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

        $jabatanFilter = trim((string) $this->jabatan);
        if ($jabatanFilter !== '') {
            $query->where(function ($sub) use ($jabatanFilter) {
                $sub->where('jabatan', $jabatanFilter)
                    ->orWhereHas('position', fn ($positionQuery) => $positionQuery->where('name', $jabatanFilter));
            });
        }

        $statusFilter = $this->normalizeStatusFilter($this->status);
        if ($statusFilter !== null) {
            $query->where('is_active', $statusFilter);
        }

        $roleFilter = trim((string) $this->role);
        if ($roleFilter !== '') {
            $query->whereRaw('LOWER(role) = ?', [Str::lower($roleFilter)]);
        }

        return $this->applySorting($query, ['id_pekerja', 'nik', 'nama_lengkap', 'email', 'is_active', 'created_at'])->paginate($this->perPage);
    }

    private function normalizeStatusFilter(?string $status): ?bool
    {
        if ($status === null || trim((string) $status) === '') {
            return null;
        }

        $normalized = Str::lower(trim((string) $status));

        return in_array($normalized, ['aktif', '1', 'true', 'yes', 'on'], true);
    }

    public function confirmDelete(int $id, string $name): void
    {
        $this->dispatch('open-confirm-modal', [
            'type'           => 'danger',
            'title'          => 'Konfirmasi Hapus',
            'message'        => "Anda akan menghapus karyawan \"{$name}\". Tindakan ini tidak dapat dibatalkan.",
            'confirmText'    => 'Ya, Hapus',
            'livewireAction' => 'deleteItem',
            'roleId'         => $id,
        ]);
    }

    public function deleteItem(int $id): void
    {
        Employee::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Karyawan berhasil dihapus.', type: 'success');
    }
}
