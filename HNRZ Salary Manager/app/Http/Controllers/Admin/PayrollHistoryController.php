<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollHistoryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = PayrollHistory::query()->with('employee.position');

        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';

            $query->whereHas('employee', function ($employeeQuery) use ($search) {
                $employeeQuery->where('nama_lengkap', 'like', $search)
                    ->orWhere('nik', 'like', $search);
            });
        }

        $query->where('payment_status', 'Sudah Dibayar')
            ->whereNotNull('payment_date')
            ->whereDate('payment_date', '>=', now()->subMonth()->toDateString())
            ->whereDate('payment_date', '<=', now()->toDateString());

        $histories = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.payroll-histories.index', compact('histories'));
    }

    public function create()
    {
        $employees = Employee::orderBy('nama_lengkap')->get();

        return view('admin.payroll-histories.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['exists:employees,id'],
            'payment_status' => ['required', 'in:Sudah Dibayar,Belum Dibayar'],
        ]);

        $employees = Employee::with('payrollMethod')->whereIn('id', $validated['employee_ids'])->get();
        $paymentStatus = $validated['payment_status'];
        $paymentDate = $paymentStatus === 'Sudah Dibayar' ? now()->toDateString() : null;
        $payrollPeriod = now()->format('Y-m');
        $warnings = [];

        DB::transaction(function () use ($employees, $paymentStatus, $paymentDate, $payrollPeriod, &$warnings) {
            foreach ($employees as $employee) {
                $bonusAmount = (int) $employee->payrollBonusesQuery($payrollPeriod)->sum('nominal_bonus');
                $bonusId = $employee->payrollBonusesQuery($payrollPeriod)->first()?->id;
                $paymentMethod = $employee->payrollMethod;
                $jabatanName = $employee->position_name;
                $gajiPokok = (int) $employee->salary;

                if (! $paymentMethod) {
                    $warnings[] = "Metode Gaji untuk \"{$employee->nama_lengkap}\" belum diatur (masih kosong).";
                }

                PayrollHistory::create([
                    'employee_id' => $employee->id,
                    'bonus_id' => $bonusId,
                    'payment_method_id' => $paymentMethod?->id,
                    'jabatan' => $jabatanName,
                    'gaji_pokok' => $gajiPokok,
                    'bonus' => $bonusAmount,
                    'total_dibayarkan' => $gajiPokok + $bonusAmount,
                    'payment_method' => $paymentMethod?->name ?? '-',
                    'payment_status' => $paymentStatus,
                    'payroll_period' => $payrollPeriod,
                    'payment_date' => $paymentDate,
                    'notes' => null,
                ]);
            }
        });

        return redirect()->route('admin.payroll-histories.index')
            ->with('success', $employees->count() . ' riwayat pembayaran gaji berhasil ditambahkan.')
            ->with('warnings', $warnings);
    }

    public function edit(PayrollHistory $payrollHistory)
    {
        $employees = Employee::orderBy('nama_lengkap')->get();

        return view('admin.payroll-histories.edit', compact('payrollHistory', 'employees'));
    }

    public function update(Request $request, PayrollHistory $payrollHistory)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'payment_status' => ['required', 'in:Sudah Dibayar,Belum Dibayar'],
        ]);

        $employee = Employee::with('payrollMethod')->findOrFail($request->employee_id);
        $payrollPeriod = $payrollHistory->payroll_period ?? now()->format('Y-m');

        $bonusAmount = (int) $employee->payrollBonusesQuery($payrollPeriod)->sum('nominal_bonus');
        $bonusId = $employee->payrollBonusesQuery($payrollPeriod)->first()?->id;
        $paymentMethod = $employee->payrollMethod;
        $jabatanName = $employee->position_name;
        $gajiPokok = (int) $employee->salary;
        $paymentStatus = $validated['payment_status'];
        $paymentDate = $paymentStatus === 'Sudah Dibayar' ? ($payrollHistory->payment_date?->toDateString() ?? now()->toDateString()) : null;

        $warnings = [];
        if (! $paymentMethod) {
            $warnings[] = "Metode Gaji untuk \"{$employee->nama_lengkap}\" belum diatur (masih kosong).";
        }

        $payrollHistory->update([
            'employee_id' => $employee->id,
            'bonus_id' => $bonusId,
            'payment_method_id' => $paymentMethod?->id,
            'jabatan' => $jabatanName,
            'gaji_pokok' => $gajiPokok,
            'bonus' => $bonusAmount,
            'total_dibayarkan' => $gajiPokok + $bonusAmount,
            'payment_method' => $paymentMethod?->name ?? '-',
            'payment_status' => $paymentStatus,
            'payroll_period' => $payrollPeriod,
            'payment_date' => $paymentDate,
            'notes' => null,
        ]);

        return redirect()->route('admin.payroll-histories.index')
            ->with('success', 'Riwayat pembayaran gaji berhasil diperbarui.')
            ->with('warnings', $warnings);
    }

    public function destroy(PayrollHistory $payrollHistory)
    {
        $payrollHistory->delete();

        return redirect()->route('admin.payroll-histories.index')
            ->with('success', 'Riwayat pembayaran gaji berhasil dihapus.');
    }
}
