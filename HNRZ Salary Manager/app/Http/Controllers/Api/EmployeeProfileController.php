<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeProfileController extends Controller
{
    /** Return the authenticated user's profile and employee details when linked. */
    public function show(Request $request)
    {
        $user = $request->user();
        $employee = $user
            ->employee()
            ->with(['position', 'payrollMethod'])
            ->first();

        if (! $employee) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama_lengkap' => $user->name,
                    'email' => $user->email,
                    'is_employee' => false,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => array_merge($employee->toArray(), ['is_employee' => true]),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
    }
}
