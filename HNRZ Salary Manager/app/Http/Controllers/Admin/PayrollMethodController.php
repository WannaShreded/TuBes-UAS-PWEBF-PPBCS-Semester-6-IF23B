<?php
// File: app/Http/Controllers/Admin/PayrollMethodController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayrollMethod;
use Illuminate\Http\Request;

class PayrollMethodController extends Controller
{
    public function index()
    {
        $payrollMethods = PayrollMethod::latest()->paginate(5);
        return view('admin.payroll-methods.index', compact('payrollMethods'));
    }

    public function create()
    {
        return view('admin.payroll-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'        => 'required|string|max:50',
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        PayrollMethod::create($validated);

        return redirect()->route('admin.payroll-methods.index')
            ->with('success', 'Metode penggajian berhasil ditambahkan.');
    }

    public function edit(PayrollMethod $payrollMethod)
    {
        return view('admin.payroll-methods.edit', compact('payrollMethod'));
    }

    public function update(Request $request, PayrollMethod $payrollMethod)
    {
        $validated = $request->validate([
            'type'        => 'required|string|max:50',
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $payrollMethod->update($validated);

        return redirect()->route('admin.payroll-methods.index')
            ->with('success', 'Metode penggajian berhasil diperbarui.');
    }

    public function destroy(PayrollMethod $payrollMethod)
    {
        if ($payrollMethod->employees()->exists()) {
            return redirect()->route('admin.payroll-methods.index')
                ->with('error', "Metode penggajian '{$payrollMethod->name}' tidak dapat dihapus karena masih digunakan oleh karyawan.");
        }

        $payrollMethod->delete();

        return redirect()->route('admin.payroll-methods.index')
            ->with('success', 'Metode penggajian berhasil dihapus.');
    }
}
