<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PayrollMethod;
use Illuminate\Http\Request;

class PayrollMethodController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data metode penggajian berhasil diambil',
            'data' => PayrollMethod::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $payrollMethod = PayrollMethod::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Metode penggajian berhasil ditambahkan',
            'data' => $payrollMethod,
        ], 201);
    }

    public function show(PayrollMethod $payrollMethod)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail metode penggajian',
            'data' => $payrollMethod,
        ]);
    }

    public function update(Request $request, PayrollMethod $payrollMethod)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', $payrollMethod->is_active);

        $payrollMethod->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Metode penggajian berhasil diperbarui',
            'data' => $payrollMethod,
        ]);
    }

    public function destroy(PayrollMethod $payrollMethod)
    {
        if ($payrollMethod->employees()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Metode penggajian '{$payrollMethod->name}' tidak dapat dihapus karena masih digunakan oleh karyawan.",
            ], 422);
        }

        $payrollMethod->delete();

        return response()->json([
            'success' => true,
            'message' => 'Metode penggajian berhasil dihapus',
        ]);
    }
}
