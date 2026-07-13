<?php

namespace App\Livewire\Admin;

use App\Models\PayrollMethod;
use Illuminate\Support\Str;

class PayrollMethodTable extends SearchableTable
{
    public string $sortField = 'created_at';
    public string $type = '';
    public string $is_active = '';
    public array $types = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'is_active' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->types = PayrollMethod::query()->distinct()->pluck('type')->filter()->values()->toArray();
    }

    protected function getView(): string
    {
        return 'livewire.admin.payroll-method-table';
    }

    public function getItems()
    {
        $query = PayrollMethod::query();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('type', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        $typeFilter = trim((string) $this->type);
        if ($typeFilter !== '') {
            $query->whereRaw('LOWER(type) = ?', [Str::lower($typeFilter)]);
        }

        $statusFilter = trim((string) $this->is_active);
        if ($statusFilter !== '') {
            $query->where('is_active', $statusFilter === '1');
        }

        return $this->applySorting($query, ['name', 'type', 'is_active', 'created_at'])->paginate($this->perPage);
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
            'message'        => "Anda akan menghapus metode gaji \"{$name}\". Tindakan ini tidak dapat dibatalkan.",
            'confirmText'    => 'Ya, Hapus',
            'livewireAction' => 'deleteItem',
            'roleId'         => $id,
        ]);
    }

    public function deleteItem(int $id): void
    {
        $payrollMethod = PayrollMethod::findOrFail($id);

        if ($payrollMethod->employees()->exists()) {
            $this->dispatch('notify', message: 'Metode gaji tidak dapat dihapus karena masih digunakan oleh karyawan.', type: 'error');
            return;
        }

        $payrollMethod->delete();
        $this->dispatch('notify', message: 'Metode gaji berhasil dihapus.', type: 'success');
    }
}
