<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;

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
            'employee_id' => ['required', 'exists:employees,id'],
            'payment_status' => ['required', 'in:Sudah Dibayar,Belum Dibayar'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $bonusAmount = (int) $employee->bonuses()->sum('nominal_bonus');
        $paymentMethod = $employee->payrollMethod()->first();
        $jabatanName = $employee->position_name;
        $gajiPokok = (int) $employee->salary;
        $paymentStatus = $validated['payment_status'];
        $paymentDate = $paymentStatus === 'Sudah Dibayar' ? now()->toDateString() : null;

        PayrollHistory::create([
            'employee_id' => $employee->id,
            'bonus_id' => $employee->bonuses()->first()?->id,
            'payment_method_id' => $paymentMethod?->id,
            'jabatan' => $jabatanName,
            'gaji_pokok' => $gajiPokok,
            'bonus' => $bonusAmount,
            'total_dibayarkan' => $gajiPokok + $bonusAmount,
            'payment_method' => $paymentMethod?->name ?? '-',
            'payment_status' => $paymentStatus,
            'payroll_period' => now()->format('Y-m'),
            'payment_date' => $paymentDate,
            'notes' => null,
        ]);

        return redirect()->route('admin.payroll-histories.index')
            ->with('success', 'Riwayat pembayaran gaji berhasil ditambahkan.');
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

        $employee = Employee::findOrFail($request->employee_id);
        $bonusAmount = (int) $employee->bonuses()->sum('nominal_bonus');
        $paymentMethod = $employee->payrollMethod()->first();
        $jabatanName = $employee->position_name;
        $gajiPokok = (int) $employee->salary;
        $paymentStatus = $validated['payment_status'];
        $paymentDate = $paymentStatus === 'Sudah Dibayar' ? ($payrollHistory->payment_date?->toDateString() ?? now()->toDateString()) : null;

        $payrollHistory->update([
            'employee_id' => $employee->id,
            'bonus_id' => $employee->bonuses()->first()?->id,
            'payment_method_id' => $paymentMethod?->id,
            'jabatan' => $jabatanName,
            'gaji_pokok' => $gajiPokok,
            'bonus' => $bonusAmount,
            'total_dibayarkan' => $gajiPokok + $bonusAmount,
            'payment_method' => $paymentMethod?->name ?? '-',
            'payment_status' => $paymentStatus,
            'payroll_period' => $payrollHistory->payroll_period ?? now()->format('Y-m'),
            'payment_date' => $paymentDate,
            'notes' => null,
        ]);

        return redirect()->route('admin.payroll-histories.index')
            ->with('success', 'Riwayat pembayaran gaji berhasil diperbarui.');
    }

    public function destroy(PayrollHistory $payrollHistory)
    {
        $payrollHistory->delete();

        return redirect()->route('admin.payroll-histories.index')
            ->with('success', 'Riwayat pembayaran gaji berhasil dihapus.');
    }
}
