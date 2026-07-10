<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayrollMethod;
use Illuminate\Http\Request;

class PayrollMethodController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'in:0,1'],
        ]);

        $query = PayrollMethod::query()
            ->when($validated['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($validated['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($validated['is_active'] ?? null, fn($q, $active) => $q->where('is_active', (bool) $active));

        $payrollMethods = $query->latest()->paginate(5)->appends($request->query());
        $types = PayrollMethod::query()->distinct()->pluck('type')->filter()->values();

        return view('admin.payroll-methods.index', compact('payrollMethods', 'types'));
    }

    public function create()
    {
        return view('admin.payroll-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
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
            'type' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
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
            ->with('success', 'Metode penggajian berhasil dipindahkan ke Recycle Bin.');
    }

    /**
     * Tampilkan daftar metode penggajian yang berada di Recycle Bin.
     */
    public function trash()
    {
        $payrollMethods = PayrollMethod::onlyTrashed()->orderByDesc('deleted_at')->paginate(5);

        return view('admin.payroll-methods.trash', compact('payrollMethods'));
    }

    /**
     * Kembalikan metode penggajian dari Recycle Bin ke data utama.
     */
    public function restore($id)
    {
        $payrollMethod = PayrollMethod::onlyTrashed()->findOrFail($id);
        $payrollMethod->restore();

        return redirect()->route('admin.payroll-methods.trash')
            ->with('success', "Metode penggajian '{$payrollMethod->name}' berhasil dipulihkan.");
    }

    /**
     * Hapus metode penggajian secara permanen dari Recycle Bin.
     */
    public function forceDelete($id)
    {
        $payrollMethod = PayrollMethod::onlyTrashed()->findOrFail($id);
        $nama = $payrollMethod->name;
        $payrollMethod->forceDelete();

        return redirect()->route('admin.payroll-methods.trash')
            ->with('success', "Metode penggajian '{$nama}' berhasil dihapus permanen.");
    }
}
