<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollMethod;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function position()
    {
        $employee = $this->currentEmployee();

        if (! $employee) {
            abort(403);
        }

        $employee->load('position');

        return view('employee.position', compact('employee'));
    }

    public function payrollMethods()
    {
        $employee = $this->currentEmployee();

        if (! $employee) {
            abort(403);
        }

        $payrollMethods = PayrollMethod::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $employee->load('payrollMethod');

        return view('employee.payroll-methods', compact('employee', 'payrollMethods'));
    }

    public function updatePayrollMethod(Request $request)
    {
        $employee = $this->currentEmployee();

        if (! $employee) {
            abort(403);
        }

        $validated = $request->validate([
            'payroll_method_id' => ['required', 'exists:payroll_methods,id'],
        ]);

        $employee->update([
            'payroll_method_id' => $validated['payroll_method_id'],
        ]);

        return redirect()->route('employee.payroll-methods.index')
            ->with('success', 'Preferensi metode penggajian berhasil diperbarui.');
    }

    protected function currentEmployee(): ?Employee
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        return $user->employee()->with(['position', 'payrollMethod'])->first();
    }
}
