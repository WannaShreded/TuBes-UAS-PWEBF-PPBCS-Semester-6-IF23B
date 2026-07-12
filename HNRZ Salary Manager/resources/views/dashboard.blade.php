{{-- File: resources/views/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:p-8">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-wide text-indigo-600">
                                Selamat datang
                            </p>
                            <h3 class="mt-2 text-2xl font-semibold text-gray-900">
                                {{ Auth::user()->name }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Role Anda:
                                <span class="font-semibold text-indigo-600">
                                    {{ Auth::user()->getRoleNames()->implode(', ') }}
                                </span>
                            </p>
                        </div>

                    @php
                        $employeeProfile = Auth::user()->employee()->with(['position', 'payrollMethod'])->first();
                    @endphp
                    @role('admin')
                        <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                            Kelola data role, user, jabatan, bonus, metode gaji, karyawan, dan riwayat gaji dari satu dashboard
                        </div>
                    @endrole
                    @role('karyawan')
                        <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                            Kelola data metode gaji dari satu dashboard
                        </div>
                    @endrole
                    </div>
                </div>

                @role('admin')
                    @php
                        $totalKaryawanAktif = \App\Models\Employee::where('is_active', true)->count();
                        $totalJabatanAktif = \App\Models\Jabatan::count();
                        $jabatanChartStats = \App\Models\Jabatan::withCount(['employees' => function ($q) {
                            $q->where('is_active', true);
                        }])->orderBy('name')->get(['id', 'name']);
                    @endphp

                    @php
                        $dashboardCards = [
                            [
                                'title' => 'Statistik',
                                'description' => 'Lihat statistik gaji & bonus per tahun dan per bulan.',
                                'route' => route('admin.statistics.index'),
                                'count' => \App\Models\PayrollHistory::selectRaw('COUNT(DISTINCT SUBSTR(payroll_period, 1, 4)) as c')->value('c') ?? 0,
                                'accent' => 'from-indigo-500 to-purple-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M8 17V9m4 8V5m4 12v-6" /></svg>',
                            ],
                            [
                                'title' => 'Role',
                                'description' => 'Kelola role dan permission untuk tiap akses.',
                                'route' => route('admin.roles.index'),
                                'count' => \Spatie\Permission\Models\Role::count(),
                                'accent' => 'from-cyan-500 to-sky-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
                            ],
                            [
                                'title' => 'User',
                                'description' => 'Kelola akun pengguna dan akses login.',
                                'route' => route('admin.users.index'),
                                'count' => \App\Models\User::count(),
                                'accent' => 'from-rose-500 to-pink-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm-8 9a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4" /></svg>',
                            ],
                            [
                                'title' => 'Jabatan',
                                'description' => 'Atur struktur jabatan dan nominal gaji.',
                                'route' => route('admin.jabatan.index'),
                                'count' => \App\Models\Jabatan::count(),
                                'accent' => 'from-violet-500 to-fuchsia-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Zm3 2.25h10M8 12h8" /></svg>',
                            ],
                            [
                                'title' => 'Bonus',
                                'description' => 'Tetapkan bonus tetap maupun variabel untuk karyawan.',
                                'route' => route('admin.bonuses.index'),
                                'count' => \App\Models\Bonus::count(),
                                'accent' => 'from-amber-500 to-orange-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m4-4H8m8 8H8M5 5h14" /></svg>',
                            ],
                            [
                                'title' => 'Metode Gaji',
                                'description' => 'Kelola metode pembayaran dan detail gaji.',
                                'route' => route('admin.payroll-methods.index'),
                                'count' => \App\Models\PayrollMethod::count(),
                                'accent' => 'from-emerald-500 to-teal-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3Zm0 0V4m0 10v6M4 12h6m4 0h6" /></svg>',
                            ],
                            [
                                'title' => 'Karyawan',
                                'description' => 'Kelola data karyawan, kontak, dan profil kerja.',
                                'route' => route('admin.employees.index'),
                                'count' => \App\Models\Employee::count(),
                                'accent' => 'from-sky-500 to-blue-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625-.372 9.337 9.337 0 0 0 3.375-2.152 9.34 9.34 0 0 0-3.375-2.152A9.38 9.38 0 0 0 15 13.128m0 6a9.38 9.38 0 0 1-2.625-.372 9.337 9.337 0 0 1-3.375-2.152 9.34 9.34 0 0 1 3.375-2.152A9.38 9.38 0 0 1 15 13.128m-6 6a9.38 9.38 0 0 0 2.625-.372 9.337 9.337 0 0 0 3.375-2.152 9.34 9.34 0 0 0-3.375-2.152A9.38 9.38 0 0 0 9 13.128m0 6v-6m0 0V7.5A2.5 2.5 0 0 1 11.5 5h1A2.5 2.5 0 0 1 15 7.5V13.128" /></svg>',
                            ],
                            [
                                'title' => 'Riwayat Gaji',
                                'description' => 'Lihat riwayat pembayaran gaji karyawan.',
                                'route' => route('admin.payroll-histories.index'),
                                'count' => \App\Models\PayrollHistory::count(),
                                'accent' => 'from-fuchsia-500 to-pink-600',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
                            ],
                        ];
                    @endphp
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($dashboardCards as $card)
                            <x-dashboard-card
                                title="{{ $card['title'] }}"
                                description="{{ $card['description'] }}"
                                href="{{ $card['route'] }}"
                                count="{{ $card['count'] }}"
                                accent="{{ $card['accent'] }}"
                            >
                                <x-slot name="icon">
                                    {!! $card['icon'] !!}
                                </x-slot>
                            </x-dashboard-card>
                        @endforeach
                    </div>
                @endrole

                @php
                    $employeeProfile = Auth::user()->employee()->with(['position', 'payrollMethod', 'bonuses'])->first();
                @endphp

                @if($employeeProfile)
                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6 shadow-sm sm:p-8">
                        <h3 class="text-lg font-semibold text-indigo-800">Informasi Profil</h3>

                        <div class="mt-6 grid grid-cols-1 gap-6 text-sm text-gray-700">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div><span class="text-gray-500">ID Pekerja</span><div class="font-semibold">{{ $employeeProfile->id_pekerja }}</div></div>
                                <div><span class="text-gray-500">NIK</span><div class="font-semibold">{{ $employeeProfile->nik }}</div></div>
                                <div><span class="text-gray-500">Nama Lengkap</span><div class="font-semibold">{{ $employeeProfile->nama_lengkap }}</div></div>
                                <div><span class="text-gray-500">Email</span><div class="font-semibold">{{ $employeeProfile->email }}</div></div>
                                <div><span class="text-gray-500">No Telepon</span><div class="font-semibold">{{ $employeeProfile->no_telepon }}</div></div>
                                <div><span class="text-gray-500">Role</span><div class="font-semibold">{{ $employeeProfile->role }}</div></div>
                            </div>

                            <div class="pt-4 border-t border-indigo-200">
                                <h4 class="mb-3 font-semibold text-indigo-800">Informasi Pekerjaan</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div><span class="text-gray-500">Nama Lengkap</span><div class="font-semibold">{{ $employeeProfile->nama_lengkap }}</div></div>
                                    <div><span class="text-gray-500">Jabatan</span><div class="font-semibold">{{ $employeeProfile->position?->name ?? $employeeProfile->jabatan ?? 'No Position Assigned' }}</div></div>
                                    <div><span class="text-gray-500">Gaji</span><div class="font-semibold">{{ $employeeProfile->position?->salary ? 'Rp ' . number_format($employeeProfile->position->salary, 0, ',', '.') : 'No Position Assigned' }}</div></div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-indigo-200">
                                <h4 class="mb-3 font-semibold text-indigo-800">Informasi Gaji</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    @php
                                        $methodType = strtolower($employeeProfile->payrollMethod?->type ?? '');
                                        $paymentMethodLabel = str_contains($methodType, 'bank') ? 'Bank' : ($employeeProfile->payrollMethod?->name ?? 'Not Selected');
                                        $bankDisplayName = $employeeProfile->nama_bank ?: preg_replace('/^(bank|e-wallet|ewallet)\s+/i', '', $employeeProfile->payrollMethod?->name ?? '');
                                    @endphp
                                    <div><span class="text-gray-500">Metode Gaji</span><div class="font-semibold">{{ $paymentMethodLabel }}</div></div>
                                    @if(str_contains($methodType, 'bank'))
                                        <div><span class="text-gray-500">Nama Bank</span><div class="font-semibold">{{ $bankDisplayName ?: 'No Bank Information' }}</div></div>
                                        <div><span class="text-gray-500">Nomor Rekening</span><div class="font-semibold">{{ $employeeProfile->nomor_rekening ?: 'No Bank Information' }}</div></div>
                                    @elseif(str_contains($methodType, 'wallet') || str_contains($methodType, 'e-wallet') || str_contains($methodType, 'ewallet'))
                                        <div><span class="text-gray-500">Nomor E-Wallet</span><div class="font-semibold">{{ $employeeProfile->nomor_e_wallet ?: 'No E-Wallet Information' }}</div></div>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-4 border-t border-indigo-200">
                                <h4 class="mb-3 font-semibold text-indigo-800">Bonus</h4>
                                @if($employeeProfile->bonuses->isNotEmpty())
                                    <ul class="list-disc pl-5 text-gray-700">
                                        @foreach($employeeProfile->bonuses as $bonus)
                                            <li>
                                                <span class="font-semibold text-gray-900">{{ $bonus->nama_bonus }}</span>
                                                <span class="text-gray-600">({{ $bonus->jenis_bonus }} - Rp {{ number_format($bonus->nominal_bonus, 0, ',', '.') }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="font-semibold text-gray-900">No Bonuses Assigned</div>
                                @endif
                            </div>

                            <div class="pt-4 border-t border-indigo-200">
                                <h4 class="mb-3 font-semibold text-indigo-800">Alamat</h4>
                                <div class="font-semibold">{{ $employeeProfile->alamat ?: 'Not Provided' }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @role('admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const canvas = document.getElementById('employeeByJabatanChart');
                if (!canvas) return;

                const statsUrl = @json(route('admin.dashboard.statistics'));

                let labels = JSON.parse(canvas.dataset.labels || '[]');
                let values = JSON.parse(canvas.dataset.values || '[]');

                const chart = new Chart(canvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Karyawan',
                            data: values,
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            maxBarThickness: 56,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'x',
                        scales: {
                            x: {
                                ticks: { autoSkip: false, maxRotation: 40, minRotation: 0 },
                                grid: { display: false },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 },
                            },
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => `${ctx.parsed.y} karyawan`,
                                },
                            },
                        },
                    },
                });

                async function refreshDashboardStatistics() {
                    try {
                        const res = await fetch(statsUrl, {
                            headers: { 'Accept': 'application/json' },
                        });
                        if (!res.ok) return;
                        const stats = await res.json();

                        const totalKaryawanEl = document.getElementById('stat-total-karyawan');
                        const totalJabatanEl = document.getElementById('stat-total-jabatan');
                        if (totalKaryawanEl) totalKaryawanEl.textContent = stats.total_karyawan_aktif;
                        if (totalJabatanEl) totalJabatanEl.textContent = stats.total_jabatan_aktif;

                        chart.data.labels = stats.chart.labels;
                        chart.data.datasets[0].data = stats.chart.data;
                        chart.update();
                    } catch (e) {
                        console.error('Gagal memperbarui statistik dashboard:', e);
                    }
                }

                setInterval(refreshDashboardStatistics, 5000);

                document.addEventListener('visibilitychange', function () {
                    if (document.visibilityState === 'visible') {
                        refreshDashboardStatistics();
                    }
                });
            });
        </script>
    @endrole
</x-app-layout>
