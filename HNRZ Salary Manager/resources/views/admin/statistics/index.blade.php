<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Statistik Gaji & Bonus
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= STATISTIC CARDS (Karyawan/Jabatan) ================= --}}
            <div class="grid gap-4 sm:grid-cols-2" id="admin-stat-cards">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Karyawan Aktif</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900" id="stat-total-karyawan">
                            {{ $totalKaryawanAktif }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-6 8v-2a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2H7Z" />
                        </svg>
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Jabatan Aktif</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900" id="stat-total-jabatan">
                            {{ $totalJabatanAktif }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Zm3 2.25h10M8 12h8" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- ================= VERTICAL BAR CHART (Karyawan per Jabatan) ================= --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200 sm:p-6">
                <h3 class="text-base font-semibold text-gray-900">Jumlah Karyawan per Jabatan</h3>
                <div class="mt-4" style="position: relative; height: 200px;">
                    <canvas id="employeeByJabatanChart"
                        data-labels='@json($jabatanChartStats->pluck("name"))'
                        data-values='@json($jabatanChartStats->pluck("employees_count"))'></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                Data gaji & bonus di bawah ini mencakup seluruh riwayat, termasuk yang berstatus <strong>Belum Dibayar</strong> (dihitung sebagai proyeksi/rencana pembayaran).
            </div>

            {{-- ================= CARD PER TAHUN (Gaji & Bonus) ================= --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-3">Total Gaji & Bonus per Tahun</h3>

                @if($yearlyTotals->isEmpty())
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 text-center text-gray-400">
                        Belum ada data riwayat gaji.
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($yearlyTotals as $yearData)
                            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                                <p class="text-sm font-medium text-gray-500">Tahun {{ $yearData->year }}</p>
                                <div class="mt-3 space-y-2">
                                    <div>
                                        <p class="text-xs text-gray-400">Total Gaji Pokok</p>
                                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($yearData->total_gaji, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">Total Bonus</p>
                                        <p class="text-xl font-bold text-amber-600">Rp {{ number_format($yearData->total_bonus, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ================= FILTER TAHUN UNTUK GRAFIK ================= --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200 sm:p-6">
                <form method="GET" action="{{ route('admin.statistics.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
                    <label class="text-sm font-medium text-gray-700">Tampilkan grafik untuk tahun:</label>
                    <select name="year" onchange="this.form.submit()" class="border rounded px-3 py-2 w-full sm:w-40">
                        @forelse($availableYears as $year)
                            <option value="{{ $year }}" {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @empty
                            <option value="{{ $selectedYear }}">{{ $selectedYear }}</option>
                        @endforelse
                    </select>
                </form>

                <h3 class="text-base font-semibold text-gray-900 mb-2">Gaji Pokok per Bulan — {{ $selectedYear }}</h3>
                <div style="position: relative; height: 220px;">
                    <canvas id="gajiPokokChart"
                        data-labels='@json($months->pluck("label"))'
                        data-values='@json($months->pluck("total_gaji"))'></canvas>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200 sm:p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-2">Bonus per Bulan — {{ $selectedYear }}</h3>
                <div style="position: relative; height: 220px;">
                    <canvas id="bonusChart"
                        data-labels='@json($months->pluck("label"))'
                        data-values='@json($months->pluck("total_bonus"))'></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function renderBarChart(canvasId, color, currency = false) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return null;

                const labels = JSON.parse(canvas.dataset.labels || '[]');
                const values = JSON.parse(canvas.dataset.values || '[]');

                return new Chart(canvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: color,
                            borderRadius: 6,
                            maxBarThickness: 40,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                ticks: currency
                                    ? { callback: (v) => 'Rp ' + Number(v).toLocaleString('id-ID') }
                                    : { precision: 0 },
                            },
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => currency
                                        ? 'Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID')
                                        : `${ctx.parsed.y} karyawan`,
                                },
                            },
                        },
                    },
                });
            }

            const employeeChart = renderBarChart('employeeByJabatanChart', 'rgba(99, 102, 241, 0.7)');
            renderBarChart('gajiPokokChart', 'rgba(99, 102, 241, 0.7)', true);
            renderBarChart('bonusChart', 'rgba(245, 158, 11, 0.7)', true);

            // Auto-refresh untuk statistik Karyawan/Jabatan (real-time, sama seperti Dashboard sebelumnya)
            const statsUrl = @json(route('admin.dashboard.statistics'));

            async function refreshEmployeeStatistics() {
                try {
                    const res = await fetch(statsUrl, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const stats = await res.json();

                    const totalKaryawanEl = document.getElementById('stat-total-karyawan');
                    const totalJabatanEl = document.getElementById('stat-total-jabatan');
                    if (totalKaryawanEl) totalKaryawanEl.textContent = stats.total_karyawan_aktif;
                    if (totalJabatanEl) totalJabatanEl.textContent = stats.total_jabatan_aktif;

                    if (employeeChart) {
                        employeeChart.data.labels = stats.chart.labels;
                        employeeChart.data.datasets[0].data = stats.chart.data;
                        employeeChart.update();
                    }
                } catch (e) {
                    console.error('Gagal memperbarui statistik karyawan/jabatan:', e);
                }
            }

            setInterval(refreshEmployeeStatistics, 5000);
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') refreshEmployeeStatistics();
            });
        });
    </script>
</x-app-layout>
