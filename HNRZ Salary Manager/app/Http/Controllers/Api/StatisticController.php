<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->validate(['year'=>['nullable','digits:4']])['year'] ?? now()->format('Y');
        $months = PayrollHistory::query()->where('payroll_period','like',$year.'-%')->selectRaw('payroll_period, SUM(gaji_pokok) total_gaji, SUM(bonus) total_bonus, SUM(total_dibayarkan) total')->groupBy('payroll_period')->orderBy('payroll_period')->get();
        return response()->json(['active_employees'=>Employee::where('is_active',true)->count(),'year'=>$year,'monthly'=>$months]);
    }
}
