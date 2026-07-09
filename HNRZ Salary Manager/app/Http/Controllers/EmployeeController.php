<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'nomor_e_wallet' => ['nullable', 'string', 'max:50'],
        ]);

        $method = PayrollMethod::find($validated['payroll_method_id']);
        $methodType = $method ? Str::lower($method->type) : '';
        $requiresBankDetails = Str::contains($methodType, 'bank');
        $requiresEwalletDetails = Str::contains($methodType, 'wallet') || Str::contains($methodType, 'e-wallet') || Str::contains($methodType, 'ewallet');

        $detailRules = [];
        if ($requiresBankDetails) {
            $detailRules['nomor_rekening'] = ['required', 'string', 'max:50'];
        } elseif ($requiresEwalletDetails) {
            $detailRules['nomor_e_wallet'] = ['required', 'string', 'max:50'];
        }

        if (! empty($detailRules)) {
            $request->validate($detailRules);
        }

        $updateData = [
            'payroll_method_id' => $validated['payroll_method_id'],
            'nama_bank' => null,
            'nomor_rekening' => null,
            'nomor_e_wallet' => null,
        ];

        if ($requiresBankDetails) {
            $updateData['nomor_rekening'] = $request->input('nomor_rekening');
        } elseif ($requiresEwalletDetails) {
            $updateData['nomor_e_wallet'] = $request->input('nomor_e_wallet');
        }

        $employee->update($updateData);

        return redirect()->route('employee.payroll-methods.index')
            ->with('success', 'Preferensi metode penggajian berhasil diperbarui.');
    }

    protected function currentEmployee(): ?Employee
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return null;
        }

        return $user->employee()->with(['position', 'payrollMethod'])->first();
    }
}
