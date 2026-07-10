<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\Employee;
use App\Models\PayrollHistory;
use App\Models\PayrollMethod;
use Illuminate\Http\Request;

class PayrollHistoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'payment_status' => ['nullable', 'in:Sudah Dibayar,Belum Dibayar'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'payroll_period' => ['nullable', 'string', 'max:20'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $query = PayrollHistory::query()->with('employee.position');

        $query->when($validated['search'] ?? null, function ($q, $search) {
            $q->whereHas('employee', function ($employeeQuery) use ($search) {
                $employeeQuery->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        });

        $query->when($validated['jabatan'] ?? null, fn ($q, $jabatan) => $q->where('jabatan', 'like', "%{$jabatan}%"));
        $query->when($validated['payment_status'] ?? null, fn ($q, $status) => $q->where('payment_status', $status));
        $query->when($validated['payment_method'] ?? null, fn ($q, $method) => $q->where('payment_method', $method));
        $query->when($validated['payroll_period'] ?? null, fn ($q, $period) => $q->where('payroll_period', 'like', "%{$period}%"));
        $query->when($validated['start_date'] ?? null, fn ($q, $date) => $q->whereDate('payment_date', '>=', $date));
        $query->when($validated['end_date'] ?? null, fn ($q, $date) => $q->whereDate('payment_date', '<=', $date));

        $histories = $query->latest()->paginate(10)->appends($request->query());
        $employees = Employee::orderBy('nama_lengkap')->get();
        $paymentMethods = PayrollMethod::query()->where('is_active', true)->pluck('name')->filter()->values();
        $jabatans = PayrollHistory::query()->distinct()->pluck('jabatan')->filter()->values();

        return view('admin.payroll-histories.index', compact('histories', 'employees', 'paymentMethods', 'jabatans'));
    }

    public function create()
    {
        $employees = Employee::orderBy('nama_lengkap')->get();
        $paymentMethods = PayrollMethod::query()->where('is_active', true)->get();
        $bonuses = Bonus::query()->orderBy('nama_bonus')->get();

        return view('admin.payroll-histories.create', compact('employees', 'paymentMethods', 'bonuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'bonus_id' => ['nullable', 'exists:bonuses,id'],
            'payment_method_id' => ['required', 'exists:payroll_methods,id'],
            'payment_status' => ['required', 'in:Sudah Dibayar,Belum Dibayar'],
            'payroll_period' => ['required', 'string', 'max:20'],
            'payment_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $bonusRecord = Bonus::find($request->bonus_id);
        $paymentMethod = PayrollMethod::findOrFail($request->payment_method_id);
        $jabatanName = $employee->position_name;
        $gajiPokok = (int) $employee->salary;
        $bonusAmount = (int) ($bonusRecord?->nominal_bonus ?? 0);
        $paymentStatus = $validated['payment_status'];
        $paymentDate = $paymentStatus === 'Sudah Dibayar' ? ($validated['payment_date'] ?? now()->toDateString()) : null;

        PayrollHistory::create([
            'employee_id' => $employee->id,
            'bonus_id' => $bonusRecord?->id,
            'payment_method_id' => $paymentMethod->id,
            'jabatan' => $jabatanName,
            'gaji_pokok' => $gajiPokok,
            'bonus' => $bonusAmount,
            'total_dibayarkan' => $gajiPokok + $bonusAmount,
            'payment_method' => $paymentMethod->name,
            'payment_status' => $paymentStatus,
            'payroll_period' => $validated['payroll_period'],
            'payment_date' => $paymentDate,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.payroll-histories.index')
            ->with('success', 'Riwayat pembayaran gaji berhasil ditambahkan.');
    }

    public function edit(PayrollHistory $payrollHistory)
    {
        $employees = Employee::orderBy('nama_lengkap')->get();
        $paymentMethods = PayrollMethod::query()->where('is_active', true)->get();
        $bonuses = Bonus::query()->orderBy('nama_bonus')->get();

        return view('admin.payroll-histories.edit', compact('payrollHistory', 'employees', 'paymentMethods', 'bonuses'));
    }

    public function update(Request $request, PayrollHistory $payrollHistory)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'bonus_id' => ['nullable', 'exists:bonuses,id'],
            'payment_method_id' => ['required', 'exists:payroll_methods,id'],
            'payment_status' => ['required', 'in:Sudah Dibayar,Belum Dibayar'],
            'payroll_period' => ['required', 'string', 'max:20'],
            'payment_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $bonusRecord = Bonus::find($request->bonus_id);
        $paymentMethod = PayrollMethod::findOrFail($request->payment_method_id);
        $jabatanName = $employee->position_name;
        $gajiPokok = (int) $employee->salary;
        $bonusAmount = (int) ($bonusRecord?->nominal_bonus ?? 0);
        $paymentStatus = $validated['payment_status'];
        $paymentDate = $paymentStatus === 'Sudah Dibayar' ? ($validated['payment_date'] ?? $payrollHistory->payment_date?->toDateString() ?? now()->toDateString()) : null;

        $payrollHistory->update([
            'employee_id' => $employee->id,
            'bonus_id' => $bonusRecord?->id,
            'payment_method_id' => $paymentMethod->id,
            'jabatan' => $jabatanName,
            'gaji_pokok' => $gajiPokok,
            'bonus' => $bonusAmount,
            'total_dibayarkan' => $gajiPokok + $bonusAmount,
            'payment_method' => $paymentMethod->name,
            'payment_status' => $paymentStatus,
            'payroll_period' => $validated['payroll_period'],
            'payment_date' => $paymentDate,
            'notes' => $validated['notes'] ?? null,
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
