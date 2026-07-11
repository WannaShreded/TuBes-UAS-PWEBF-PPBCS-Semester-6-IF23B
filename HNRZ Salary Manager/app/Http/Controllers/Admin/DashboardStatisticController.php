<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Jabatan;
use Illuminate\Http\JsonResponse;

class DashboardStatisticController extends Controller
{
    /**
     * Mengembalikan statistik real-time untuk Dashboard Admin:
     * - Total karyawan aktif (tidak termasuk soft-deleted / non-aktif)
     * - Total jabatan aktif (tidak termasuk soft-deleted)
     * - Data grafik jumlah karyawan per jabatan (GROUP BY jabatan_id)
     */
    public function index(): JsonResponse
    {
        $totalKaryawanAktif = Employee::query()
            ->where('is_active', true)
            ->count();

        $totalJabatanAktif = Jabatan::query()->count();

        $jabatanStats = Jabatan::query()
            ->withCount(['employees' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(function (Jabatan $jabatan) {
                return [
                    'label' => $jabatan->name,
                    'total' => $jabatan->employees_count,
                ];
            });

        return response()->json([
            'total_karyawan_aktif' => $totalKaryawanAktif,
            'total_jabatan_aktif' => $totalJabatanAktif,
            'chart' => [
                'labels' => $jabatanStats->pluck('label'),
                'data' => $jabatanStats->pluck('total'),
            ],
        ]);
    }
}
