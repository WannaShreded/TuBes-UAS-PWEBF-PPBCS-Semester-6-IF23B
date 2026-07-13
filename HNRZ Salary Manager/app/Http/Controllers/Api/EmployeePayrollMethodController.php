<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PayrollMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeePayrollMethodController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee()->first();
        if (! $employee) {
            return response()->json(['message' => 'Akun belum terhubung dengan data karyawan.'], 403);
        }

        return response()->json([
            'data' => PayrollMethod::query()->where('is_active', true)->orderBy('name')->get(),
            'selected_id' => $employee->payroll_method_id,
            'nomor_rekening' => $employee->nomor_rekening,
            'nomor_e_wallet' => $employee->nomor_e_wallet,
        ]);
    }

    public function update(Request $request)
    {
        $employee = $request->user()->employee()->first();
        if (! $employee) {
            return response()->json(['message' => 'Akun belum terhubung dengan data karyawan.'], 403);
        }

        $validated = $request->validate([
            'payroll_method_id' => ['required', 'exists:payroll_methods,id'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'nomor_e_wallet' => ['nullable', 'string', 'max:50'],
        ]);

        $method = PayrollMethod::query()->where('is_active', true)->find($validated['payroll_method_id']);
        if (! $method) {
            return response()->json(['message' => 'Metode gaji tidak tersedia.'], 422);
        }

        $type = Str::lower($method->type);
        $isBank = Str::contains($type, 'bank');
        $isEwallet = Str::contains($type, ['wallet', 'e-wallet', 'ewallet']);
        if ($isBank && empty($validated['nomor_rekening'])) {
            return response()->json(['message' => 'Nomor rekening wajib diisi.'], 422);
        }
        if ($isEwallet && empty($validated['nomor_e_wallet'])) {
            return response()->json(['message' => 'Nomor e-wallet wajib diisi.'], 422);
        }

        $employee->update([
            'payroll_method_id' => $method->id,
            'nomor_rekening' => $isBank ? $validated['nomor_rekening'] : null,
            'nomor_e_wallet' => $isEwallet ? $validated['nomor_e_wallet'] : null,
        ]);

        return response()->json(['success' => true, 'message' => 'Metode gaji berhasil diperbarui.']);
    }
}
