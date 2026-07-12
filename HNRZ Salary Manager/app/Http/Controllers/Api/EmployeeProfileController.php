<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
