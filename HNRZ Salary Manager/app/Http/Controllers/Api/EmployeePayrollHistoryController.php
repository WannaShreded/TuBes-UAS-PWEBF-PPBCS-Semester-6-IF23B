<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;

class EmployeePayrollHistoryController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee()->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun belum terhubung dengan data karyawan.'
            ], 403);
        }

        $histories = PayrollHistory::query()
            ->where('employee_id', $employee->id)
            ->orderByDesc('payroll_period')
            ->get();

        $allHistories = PayrollHistory::query()
            ->where('employee_id', $employee->id)
            ->where('payment_status', 'Sudah Dibayar')
            ->get();

        $totalPayroll = $allHistories->sum('total_dibayarkan');
        $averagePayroll = $allHistories->avg('total_dibayarkan') ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_payroll' => (int) $totalPayroll,
                'average_payroll' => (int) $averagePayroll,
                'histories' => $histories
            ]
        ]);
    }
}
