<?php

namespace App\Livewire\Admin;

use App\Models\PayrollHistory;

class PayrollHistoryTable extends SearchableTable
{
    public string $sortField = 'created_at';
    public string $payment_status = '';
    public string $payroll_period = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'payment_status' => ['except' => ''],
        'payroll_period' => ['except' => ''],
    ];

    protected function getView(): string
    {
        return 'livewire.admin.payroll-history-table';
    }

    public function getItems()
    {
        $query = PayrollHistory::query()->with('employee.position');

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->whereHas('employee', function ($employeeQuery) use ($search) {
                $employeeQuery->where('nama_lengkap', 'like', $search)
                    ->orWhere('nik', 'like', $search);
            });
        }

        $status = trim($this->payment_status);
        if ($status !== '') {
            $query->where('payment_status', $status);
        }

        $period = trim($this->payroll_period);
        if ($period !== '') {
            $query->where('payroll_period', $period);
        }

        return $this->applySorting($query, ['payroll_period', 'total_dibayarkan', 'payment_status', 'payment_date', 'created_at'])->paginate($this->perPage);
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
