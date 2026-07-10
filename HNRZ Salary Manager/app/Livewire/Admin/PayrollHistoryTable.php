<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\PayrollHistory;
use Illuminate\Support\Str;

class PayrollHistoryTable extends SearchableTable
{
    public string $employee_id = '';
    public string $jabatan = '';
    public string $payment_status = '';
    public string $payment_method = '';
    public string $payroll_period = '';
    public string $start_date = '';
    public string $end_date = '';
    public array $employeeOptions = [];
    public array $paymentMethods = [];
    public array $jabatans = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'employee_id' => ['except' => ''],
        'jabatan' => ['except' => ''],
        'payment_status' => ['except' => ''],
        'payment_method' => ['except' => ''],
        'payroll_period' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->employeeOptions = Employee::query()
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nik'])
            ->map(fn ($employee) => [
                'id' => $employee->id,
                'label' => $employee->nama_lengkap . ' (' . $employee->nik . ')',
            ])
            ->toArray();

        $this->paymentMethods = PayrollHistory::query()
            ->distinct()
            ->pluck('payment_method')
            ->filter()
            ->values()
            ->toArray();

        $this->jabatans = PayrollHistory::query()
            ->distinct()
            ->pluck('jabatan')
            ->filter()
            ->values()
            ->toArray();
    }

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

        $employeeId = trim((string) $this->employee_id);
        if ($employeeId !== '') {
            $query->where('employee_id', $employeeId);
        }

        $jabatanFilter = trim((string) $this->jabatan);
        if ($jabatanFilter !== '') {
            $query->where('jabatan', 'like', '%' . $jabatanFilter . '%');
        }

        $paymentStatus = trim((string) $this->payment_status);
        if ($paymentStatus !== '') {
            $query->where('payment_status', $paymentStatus);
        }

        $paymentMethod = trim((string) $this->payment_method);
        if ($paymentMethod !== '') {
            $query->whereRaw('LOWER(payment_method) = ?', [Str::lower($paymentMethod)]);
        }

        $payrollPeriod = trim((string) $this->payroll_period);
        if ($payrollPeriod !== '') {
            $query->where('payroll_period', 'like', $payrollPeriod . '%');
        }

        $startDate = trim((string) $this->start_date);
        if ($startDate !== '') {
            $query->whereDate('payment_date', '>=', $startDate);
        }

        $endDate = trim((string) $this->end_date);
        if ($endDate !== '') {
            $query->whereDate('payment_date', '<=', $endDate);
        }

        return $query->paginate($this->perPage);
    }
}
