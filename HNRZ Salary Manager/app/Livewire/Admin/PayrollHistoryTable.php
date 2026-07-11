<?php

namespace App\Livewire\Admin;

use App\Models\PayrollHistory;

class PayrollHistoryTable extends SearchableTable
{
    protected function getView(): string
    {
        return 'livewire.admin.payroll-history-table';
    }

    public function getItems()
    {
        $query = PayrollHistory::query()->with('employee.position')->latest();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->whereHas('employee', function ($employeeQuery) use ($search) {
                $employeeQuery->where('nama_lengkap', 'like', $search)
                    ->orWhere('nik', 'like', $search);
            });
        }

        $query->where('payment_status', 'Sudah Dibayar')
            ->whereNotNull('payment_date')
            ->whereDate('payment_date', '>=', now()->subMonth()->toDateString())
            ->whereDate('payment_date', '<=', now()->toDateString());

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
            'message'        => "Anda akan menghapus {$name}. Tindakan ini tidak dapat dibatalkan.",
            'confirmText'    => 'Ya, Hapus',
            'livewireAction' => 'deleteItem',
            'roleId'         => $id,
        ]);
    }

    public function deleteItem(int $id): void
    {
        PayrollHistory::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Riwayat gaji berhasil dihapus.', type: 'success');
    }
}
