<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PayrollHistory::query()->with('employee:id,nama_lengkap,nik')->latest();
        if ($search = trim((string) $request->input('search'))) {
            $query->whereHas('employee', fn ($q) => $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%"));
        }
        if ($request->filled('payment_status')) $query->where('payment_status', $request->string('payment_status'));
        if ($request->filled('payroll_period')) $query->where('payroll_period', $request->string('payroll_period'));
        return response()->json(['data' => $query->paginate(min(max((int) $request->input('per_page', 15), 1), 50))]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['employee_ids' => ['required','array','min:1'], 'employee_ids.*' => ['exists:employees,id'], 'payroll_period' => ['required','date_format:Y-m'], 'payment_status' => ['required','in:Sudah Dibayar,Belum Dibayar']]);
        $employees = Employee::with('payrollMethod')->whereIn('id', $data['employee_ids'])->get();
        DB::transaction(function () use ($employees, $data) {
            foreach ($employees as $employee) {
                $bonus = (int) $employee->payrollBonusesQuery($data['payroll_period'])->sum('nominal_bonus');
                $method = $employee->payrollMethod;
                PayrollHistory::create(['employee_id'=>$employee->id,'bonus_id'=>$employee->payrollBonusesQuery($data['payroll_period'])->first()?->id,'payment_method_id'=>$method?->id,'jabatan'=>$employee->position_name,'gaji_pokok'=>(int)$employee->salary,'bonus'=>$bonus,'total_dibayarkan'=>(int)$employee->salary+$bonus,'payment_method'=>$method?->name ?? '-','payment_status'=>$data['payment_status'],'payroll_period'=>$data['payroll_period'],'payment_date'=>$data['payment_status']==='Sudah Dibayar' ? now()->toDateString() : null]);
            }
        });
        return response()->json(['success'=>true,'message'=>count($employees).' riwayat payroll berhasil dibuat.'], 201);
    }

    public function destroy(PayrollHistory $payrollHistory) { $payrollHistory->delete(); return response()->json(['success'=>true,'message'=>'Riwayat payroll berhasil dihapus.']); }
}
