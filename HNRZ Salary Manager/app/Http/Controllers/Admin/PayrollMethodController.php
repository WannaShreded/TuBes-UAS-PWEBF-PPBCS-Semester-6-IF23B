<?php
// File: app/Http/Controllers/Admin/PayrollMethodController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayrollMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayrollMethodController extends Controller
{
    public function index()
    {
        $payrollMethods = PayrollMethod::latest()->paginate(10);
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

        $validated['code'] = $this->generateUniqueCode($validated['type'], $validated['name']);
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

        // Regenerate kode kalau tipe atau nama berubah
        if ($validated['type'] !== $payrollMethod->type || $validated['name'] !== $payrollMethod->name) {
            $validated['code'] = $this->generateUniqueCode($validated['type'], $validated['name'], $payrollMethod->id);
        }

        $payrollMethod->update($validated);

        return redirect()->route('admin.payroll-methods.index')
            ->with('success', 'Metode penggajian berhasil diperbarui.');
    }

    public function destroy(PayrollMethod $payrollMethod)
    {
        $payrollMethod->delete();

        return redirect()->route('admin.payroll-methods.index')
            ->with('success', 'Metode penggajian berhasil dihapus.');
    }

    /**
     * Buat kode unik otomatis, contoh: BANK-BCAUTAMA, EWALLET-GOPAYSTAFF
     * $ignoreId dipakai saat update, biar tidak bentrok sama kode dirinya sendiri
     */
    private function generateUniqueCode(string $type, string $name, ?int $ignoreId = null): string
    {
        $base = strtoupper(Str::slug($type, '')) . '-' . strtoupper(Str::slug($name, ''));
        $code = $base;
        $i = 1;

        $query = fn ($code) => PayrollMethod::where('code', $code)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        while ($query($code)) {
            $code = $base . '-' . $i;
            $i++;
        }

        return $code;
    }
}
