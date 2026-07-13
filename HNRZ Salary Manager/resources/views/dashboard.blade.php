{{-- File: resources/views/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-[#241a52] font-display">
                    Dashboard
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <div class="rounded-[22px] border border-[#ece7fb] bg-white p-6 shadow-sm shadow-[#5b1fb8]/5 sm:p-8 transition-all duration-300 hover:shadow-md">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#e91e8c] font-display">
                                Selamat datang
                            </p>
                            <h3 class="mt-2 text-2xl font-bold text-[#241a52] font-display">
                                {{ Auth::user()->name }}
                            </h3>
                            <p class="mt-2 text-sm text-[#5b5578]">
                                Role Anda:
                                <span class="font-semibold text-[#7c1fd6]">
                                    {{ Auth::user()->getRoleNames()->implode(', ') }}
                                </span>
                            </p>
                        </div>

                    @php
                        $employeeProfile = Auth::user()->employee()->with(['position', 'payrollMethod'])->first();
                    @endphp
                    @role('admin')
                        <div class="rounded-[14px] border border-[#7c1fd6]/20 bg-[#7c1fd6]/5 px-4 py-3 text-sm text-[#7c1fd6] font-medium">
                            Kelola data role, user, jabatan, bonus, metode gaji, karyawan, dan riwayat gaji dari satu dashboard
                        </div>
                    @endrole
                    @role('karyawan')
                        <div class="rounded-[14px] border border-[#7c1fd6]/20 bg-[#7c1fd6]/5 px-4 py-3 text-sm text-[#7c1fd6] font-medium">
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

                    @php
                        $latestPeriod = \App\Models\PayrollHistory::max('payroll_period');
                        $periodPayroll = $latestPeriod ? \App\Models\PayrollHistory::where('payroll_period', $latestPeriod) : \App\Models\PayrollHistory::query()->whereRaw('1 = 0');
                        $trend = \App\Models\PayrollHistory::query()->selectRaw('payroll_period, SUM(total_dibayarkan) as total')->groupBy('payroll_period')->orderByDesc('payroll_period')->limit(6)->get()->reverse()->values();
                    @endphp
                    <section class="mt-8" aria-labelledby="payroll-overview-heading">
                        <div class="mb-4">
                            <h3 id="payroll-overview-heading" class="text-lg font-bold text-[#241a52] font-display">Ringkasan payroll</h3>
                            <p class="mt-1 text-sm text-[#5b5578]">Status periode {{ $latestPeriod ?? 'belum tersedia' }}.</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="app-surface p-5 hover:shadow-md transition-all duration-300">
                                <p class="text-sm font-semibold text-[#5b5578]">Karyawan aktif</p>
                                <p id="stat-total-karyawan" class="mt-2 text-2xl font-bold text-[#241a52] font-display">{{ $totalKaryawanAktif }}</p>
                            </div>
                            <div class="app-surface p-5 hover:shadow-md transition-all duration-300">
                                <p class="text-sm font-semibold text-[#5b5578]">Total gaji periode</p>
                                <p class="mt-2 text-2xl font-bold text-[#241a52] font-display">Rp {{ number_format((int) $periodPayroll->sum('total_dibayarkan'), 0, ',', '.') }}</p>
                            </div>
                            <div class="app-surface p-5 hover:shadow-md transition-all duration-300">
                                <p class="text-sm font-semibold text-[#5b5578]">Pembayaran tercatat</p>
                                <p class="mt-2 text-2xl font-bold text-[#241a52] font-display">{{ $periodPayroll->count() }}</p>
                            </div>
                            <div class="app-surface p-5 hover:shadow-md transition-all duration-300">
                                <p class="text-sm font-semibold text-[#5b5578]">Menunggu proses</p>
                                <p class="mt-2 text-2xl font-bold text-[#241a52] font-display">{{ (clone $periodPayroll)->whereRaw('LOWER(payment_status) in (?, ?)', ['pending', 'menunggu'])->count() }}</p>
                            </div>
                        </div>
                        <div class="rounded-[22px] border border-[#ece7fb] bg-white mt-4 p-5 sm:p-6 shadow-sm shadow-[#5b1fb8]/5 hover:shadow-xl hover:shadow-[#5b1fb8]/10 transition-all duration-300">
                            <div class="mb-4">
                                <h4 class="font-bold text-[#241a52] font-display text-base">Tren biaya payroll</h4>
                                <p class="text-sm text-[#5b5578]">Enam periode payroll terakhir.</p>
                            </div>
                            <div class="h-72">
                                <canvas id="payrollCostTrend" data-labels='@json($trend->pluck("payroll_period"))' data-values='@json($trend->pluck("total"))'></canvas>
                            </div>
                        </div>
                    </section>
                @endrole

                @php
                    $employeeProfile = Auth::user()->employee()->with(['position', 'payrollMethod', 'bonuses'])->first();
                @endphp

                @if($employeeProfile)
                    <div class="rounded-[22px] border border-[#ece7fb] bg-white p-6 shadow-sm shadow-[#5b1fb8]/5 sm:p-8 hover:shadow-xl hover:shadow-[#5b1fb8]/10 transition-all duration-300">
                        <h3 class="text-lg font-bold text-[#241a52] font-display border-b border-[#ece7fb] pb-3 mb-6">Informasi Profil</h3>

                        <div class="grid grid-cols-1 gap-6 text-sm text-[#5b5578]">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">ID Pekerja</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->id_pekerja }}</div></div>
                                <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">NIK</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->nik }}</div></div>
                                <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Nama Lengkap</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->nama_lengkap }}</div></div>
                                <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Email</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->email }}</div></div>
                                <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">No Telepon</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->no_telepon }}</div></div>
                                <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Role</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->role }}</div></div>
                            </div>

                            <div class="pt-6 border-t border-[#ece7fb]">
                                <h4 class="mb-4 font-bold text-[#7c1fd6] font-display text-base">Informasi Pekerjaan</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Nama Lengkap</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->nama_lengkap }}</div></div>
                                    <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Jabatan</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->position?->name ?? $employeeProfile->jabatan ?? 'No Position Assigned' }}</div></div>
                                    <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Gaji</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->position?->salary ? 'Rp ' . number_format($employeeProfile->position->salary, 0, ',', '.') : 'No Position Assigned' }}</div></div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-[#ece7fb]">
                                <h4 class="mb-4 font-bold text-[#7c1fd6] font-display text-base">Informasi Gaji</h4>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    @php
                                        $methodType = strtolower($employeeProfile->payrollMethod?->type ?? '');
                                        $paymentMethodLabel = str_contains($methodType, 'bank') ? 'Bank' : ($employeeProfile->payrollMethod?->name ?? 'Not Selected');
                                        $bankDisplayName = $employeeProfile->nama_bank ?: preg_replace('/^(bank|e-wallet|ewallet)\s+/i', '', $employeeProfile->payrollMethod?->name ?? '');
                                    @endphp
                                    <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Metode Gaji</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $paymentMethodLabel }}</div></div>
                                    @if(str_contains($methodType, 'bank'))
                                        <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Nama Bank</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $bankDisplayName ?: 'No Bank Information' }}</div></div>
                                        <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Nomor Rekening</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->nomor_rekening ?: 'No Bank Information' }}</div></div>
                                    @elseif(str_contains($methodType, 'wallet') || str_contains($methodType, 'e-wallet') || str_contains($methodType, 'ewallet'))
                                        <div><span class="text-[#5b5578] text-xs font-semibold uppercase tracking-wider">Nomor E-Wallet</span><div class="font-bold text-[#241a52] text-sm mt-1">{{ $employeeProfile->nomor_e_wallet ?: 'No E-Wallet Information' }}</div></div>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-6 border-t border-[#ece7fb]">
                                <h4 class="mb-4 font-bold text-[#7c1fd6] font-display text-base">Bonus</h4>
                                @if($employeeProfile->bonuses->isNotEmpty())
                                    <ul class="list-disc pl-5 text-[#5b5578] space-y-2">
                                        @foreach($employeeProfile->bonuses as $bonus)
                                            <li>
                                                <span class="font-bold text-[#241a52]">{{ $bonus->nama_bonus }}</span>
                                                <span class="text-[#5b5578]">({{ $bonus->jenis_bonus }} - Rp {{ number_format($bonus->nominal_bonus, 0, ',', '.') }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="font-bold text-[#241a52] text-sm">Belum Ada Bonus yang Ditugaskan</div>
                                @endif
                            </div>

                            <div class="pt-6 border-t border-[#ece7fb]">
                                <h4 class="mb-4 font-bold text-[#7c1fd6] font-display text-base">Alamat</h4>
                                <div class="font-bold text-[#241a52] text-sm">{{ $employeeProfile->alamat ?: 'Belum Disediakan' }}</div>
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
                const canvas = document.getElementById('payrollCostTrend');
                if (!canvas) return;

                const labels = JSON.parse(canvas.dataset.labels || '[]');
                const values = JSON.parse(canvas.dataset.values || '[]');

                const chart = new Chart(canvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total biaya payroll',
                            data: values,
                            borderColor: '#7c1fd6', 
                            backgroundColor: 'rgba(124, 31, 214, 0.08)', 
                            borderWidth: 3, 
                            fill: true, 
                            tension: 0.3, 
                            pointRadius: 4,
                            pointBackgroundColor: '#e91e8c',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 300 },
                        scales: {
                            x: {
                                ticks: { autoSkip: false, maxRotation: 40, minRotation: 0 },
                                grid: { display: false },
                            },
                            y: {
                                beginAtZero: true, ticks: { callback: value => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value) },
                            },
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y),
                                },
                            },
                        },
                    },
                });

            });
        </script>
    @endrole
</x-app-layout>

