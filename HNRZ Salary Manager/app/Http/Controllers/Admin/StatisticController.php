<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Jabatan;
use App\Models\PayrollHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'year' => ['nullable', 'digits:4'],
        ]);

        // ===== Data Karyawan & Jabatan (dipindahkan dari Dashboard) =====
        $totalKaryawanAktif = Employee::where('is_active', true)->count();
        $totalJabatanAktif = Jabatan::count();
        $jabatanChartStats = Jabatan::withCount(['employees' => function ($q) {
            $q->where('is_active', true);
        }])->orderBy('name')->get(['id', 'name']);

        // ===== Daftar tahun yang tersedia dari data payroll_period =====
        $availableYears = PayrollHistory::query()
            ->selectRaw('SUBSTR(payroll_period, 1, 4) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $selectedYear = $request->input('year', $availableYears->first() ?? now()->format('Y'));

        // ===== CARD: Total per tahun =====
        $yearlyTotals = PayrollHistory::query()
            ->selectRaw('SUBSTR(payroll_period, 1, 4) as year, SUM(gaji_pokok) as total_gaji, SUM(bonus) as total_bonus')
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();

        // ===== CHART: Per bulan untuk tahun yang dipilih =====
        $monthlyRaw = PayrollHistory::query()
            ->where('payroll_period', 'like', $selectedYear . '-%')
            ->selectRaw('payroll_period, SUM(gaji_pokok) as total_gaji, SUM(bonus) as total_bonus')
            ->groupBy('payroll_period')
            ->get()
            ->keyBy('payroll_period');

        $months = collect(range(1, 12))->map(function ($m) use ($selectedYear, $monthlyRaw) {
            $period = $selectedYear . '-' . str_pad((string) $m, 2, '0', STR_PAD_LEFT);
            $row = $monthlyRaw->get($period);

            return [
                'period' => $period,
                'label' => Carbon::createFromFormat('Y-m', $period)->translatedFormat('M Y'),
                'total_gaji' => (int) ($row->total_gaji ?? 0),
                'total_bonus' => (int) ($row->total_bonus ?? 0),
            ];
        });

        return view('admin.statistics.index', compact(
            'totalKaryawanAktif', 'totalJabatanAktif', 'jabatanChartStats',
            'availableYears', 'selectedYear', 'yearlyTotals', 'months'
        ));
    }
}
