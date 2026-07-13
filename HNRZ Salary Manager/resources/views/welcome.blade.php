<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HNRZ') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|inter:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        .font-display {
            font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif;
        }

        .cosmic-band {
            background: linear-gradient(135deg, #140b3d 0%, #5b1fb8 55%, #7c1fd6 100%);
        }

        .orb {
            animation: floatY 5.5s ease-in-out infinite;
        }

        .orb-b {
            animation-delay: .6s;
        }

        .orb-c {
            animation-delay: 1.1s;
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-14px);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .orb {
                animation: none;
            }
        }
    </style>
</head>

<body class="bg-white text-[#241a52] antialiased">

    {{-- ============ HERO / COSMIC BAND ============ --}}
    <section class="relative overflow-hidden cosmic-band pb-24">

        {{-- Decorative streak field --}}
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <svg viewBox="0 0 1400 620" preserveAspectRatio="xMidYMid slice" class="w-full h-full">
                <defs>
                    <linearGradient id="streakPink" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#e91e8c" />
                        <stop offset="100%" stop-color="#5b1fb8" />
                    </linearGradient>
                    <linearGradient id="streakCyan" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#22d3ee" />
                        <stop offset="100%" stop-color="#5b1fb8" />
                    </linearGradient>
                    <radialGradient id="orbCyan" cx="35%" cy="30%" r="70%">
                        <stop offset="0%" stop-color="#a6f3ff" />
                        <stop offset="55%" stop-color="#22d3ee" />
                        <stop offset="100%" stop-color="#0e7fa8" />
                    </radialGradient>
                </defs>

                <g stroke="#fbbf24" stroke-width="3" stroke-linecap="round" opacity="0.85">
                    <line x1="120" y1="560" x2="260" y2="380" />
                    <line x1="340" y1="600" x2="480" y2="400" />
                    <line x1="560" y1="580" x2="700" y2="360" />
                    <line x1="780" y1="600" x2="920" y2="380" />
                    <line x1="1000" y1="560" x2="1140" y2="360" />
                    <line x1="1180" y1="600" x2="1320" y2="400" />
                </g>

                <g stroke-linecap="round">
                    <line x1="60" y1="80" x2="200" y2="-40" stroke="url(#streakPink)"
                        stroke-width="16" opacity="0.9" />
                    <line x1="260" y1="140" x2="420" y2="-20" stroke="url(#streakCyan)"
                        stroke-width="14" opacity="0.85" />
                    <line x1="480" y1="200" x2="640" y2="40" stroke="url(#streakPink)"
                        stroke-width="18" opacity="0.9" />
                    <line x1="700" y1="120" x2="860" y2="-30" stroke="url(#streakCyan)"
                        stroke-width="12" opacity="0.8" />
                    <line x1="900" y1="220" x2="1060" y2="60" stroke="url(#streakPink)"
                        stroke-width="16" opacity="0.85" />
                    <line x1="1120" y1="140" x2="1280" y2="-10" stroke="url(#streakCyan)"
                        stroke-width="14" opacity="0.85" />
                </g>

                <g fill="none" stroke="#a78bfa" stroke-width="3" opacity="0.55" stroke-linecap="round">
                    <path d="M40 260 q20 -30 0 -60 t0 -60" />
                    <path d="M1240 260 q20 -30 0 -60 t0 -60" />
                    <path d="M1040 130 q20 -28 0 -56 t0 -56" />
                </g>

                <g>
                    <circle cx="205" cy="170" r="34" fill="url(#orbCyan)" class="orb" />
                    <circle cx="470" cy="260" r="16" fill="url(#orbCyan)" class="orb orb-b" />
                    <circle cx="1000" cy="180" r="26" fill="url(#orbCyan)" class="orb" />
                    <circle cx="1150" cy="290" r="12" fill="url(#orbCyan)" class="orb orb-c" />
                    <circle cx="1250" cy="230" r="18" fill="url(#orbCyan)" class="orb orb-b" />
                </g>
            </svg>
        </div>

        {{-- ============ NAV ============ --}}
        <nav class="relative z-10 flex flex-wrap items-center justify-between gap-5 px-6 pt-9 sm:px-10 lg:px-14">

            <a href="{{ url('/') }}"
                class="flex items-center gap-2.5 font-display text-lg font-bold tracking-wide text-white">

                <span class="relative inline-block h-20 w-20">
                    <img src="{{ asset('images/logoHNRZikiPutih.png') }}" alt="HNRZ Salary Manager"
                        class="h-full w-full object-contain">
                </span>

                HNRZ
                <span class="font-normal text-white/75">
                    Salary Manager
                </span>
            </a>

            @if (Route::has('login'))
                <div class="flex items-center gap-4 font-display text-sm font-semibold tracking-wide">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="rounded-full bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] px-6 py-2.5 text-white shadow-lg shadow-[#e91e8c]/30 transition hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="rounded-full bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] px-6 py-2.5 text-white shadow-lg shadow-[#e91e8c]/30 transition hover:-translate-y-0.5">
                            Masuk
                        </a>
                    @endauth
                </div>
            @endif

        </nav>

        {{-- ============ HERO COPY ============ --}}
        <div class="relative z-10 mx-auto max-w-2xl px-6 pt-16 text-center sm:pt-20">
            <span class="mb-4 inline-block font-display text-xs font-bold uppercase tracking-[0.22em] text-[#fbbf24]">
                Sistem Penggajian Karyawan
            </span>
            <h1 class="font-display text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">
                Kelola Gaji, Tanpa Ribet
            </h1>
            <p class="mt-5 text-base leading-relaxed text-white/75 sm:text-lg">
                Hitung gaji, tunjangan, potongan, dan slip gaji karyawan dalam satu tempat.
                Cepat, akurat, dan mudah diaudit setiap bulan.
            </p>

            <div class="mt-9 flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="rounded-full bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] px-9 py-4 font-display text-sm font-bold uppercase tracking-[0.14em] text-white shadow-lg shadow-[#e91e8c]/40 transition hover:-translate-y-0.5">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-full bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] px-9 py-4 font-display text-sm font-bold uppercase tracking-[0.14em] text-white shadow-lg shadow-[#e91e8c]/40 transition hover:-translate-y-0.5">
                        Masuk ke Akun
                    </a>

                @endauth
            </div>
        </div>

        {{-- ============ WAVE DIVIDER ============ --}}
        <div class="relative z-10 mt-24">
            <svg viewBox="0 0 1400 120" preserveAspectRatio="none" class="block h-auto w-full -mb-px">
                <path fill="#ffffff" d="M0,80 C240,140 420,10 700,45 C980,80 1160,10 1400,55 L1400,120 L0,120 Z">
                </path>
            </svg>
        </div>
    </section>

    {{-- ============ FEATURES ============ --}}
    <section class="bg-white px-6 pb-24 pt-2 sm:px-10 lg:px-14">
        <div class="mx-auto max-w-xl text-center">
            <span class="mb-3 block font-display text-xs font-bold uppercase tracking-[0.2em] text-[#e91e8c]">
                Fitur Utama
            </span>
            <h2 class="font-display text-3xl font-bold text-[#241a52] sm:text-4xl">
                Semua Kebutuhan Payroll, Satu Aplikasi
            </h2>
            <p class="mt-4 leading-relaxed text-[#5b5578]">
                Dirancang untuk tim HR dan finance yang butuh proses penggajian yang cepat dan transparan.
            </p>
        </div>

        <div class="mx-auto mt-14 grid max-w-6xl gap-7 sm:grid-cols-2 lg:grid-cols-3">
            <div
                class="rounded-[22px] border border-[#ece7fb] bg-[#f4f1fb] p-8 transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#5b1fb8]/10">
                <div class="mb-5 flex h-13 w-13 items-center justify-center rounded-2xl bg-gradient-to-br from-[#22d3ee] to-[#7c1fd6] font-display text-lg font-bold text-white"
                    style="width:52px;height:52px;">
                    01
                </div>
                <h3 class="mb-2 font-display text-lg font-bold text-[#241a52]">Hitung Gaji Otomatis</h3>
                <p class="text-sm leading-relaxed text-[#5b5578]">
                    Gaji pokok, lembur, tunjangan, hingga potongan pajak dihitung otomatis setiap periode.
                </p>
            </div>

            <div
                class="rounded-[22px] border border-[#ece7fb] bg-[#f4f1fb] p-8 transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#5b1fb8]/10">
                <div class="mb-5 flex h-13 w-13 items-center justify-center rounded-2xl bg-gradient-to-br from-[#22d3ee] to-[#7c1fd6] font-display text-lg font-bold text-white"
                    style="width:52px;height:52px;">
                    02
                </div>
                <h3 class="mb-2 font-display text-lg font-bold text-[#241a52]">Slip Gaji Digital</h3>
                <p class="text-sm leading-relaxed text-[#5b5578]">
                    Karyawan bisa mengunduh slip gaji kapan saja langsung dari akun masing-masing.
                </p>
            </div>

            <div
                class="rounded-[22px] border border-[#ece7fb] bg-[#f4f1fb] p-8 transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#5b1fb8]/10">
                <div class="mb-5 flex h-13 w-13 items-center justify-center rounded-2xl bg-gradient-to-br from-[#22d3ee] to-[#7c1fd6] font-display text-lg font-bold text-white"
                    style="width:52px;height:52px;">
                    03
                </div>
                <h3 class="mb-2 font-display text-lg font-bold text-[#241a52]">Absensi &amp; Cuti</h3>
                <p class="text-sm leading-relaxed text-[#5b5578]">
                    Terhubung dengan data kehadiran dan cuti, jadi potongan otomatis mengikuti data nyata.
                </p>
            </div>

            <div
                class="rounded-[22px] border border-[#ece7fb] bg-[#f4f1fb] p-8 transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#5b1fb8]/10">
                <div class="mb-5 flex h-13 w-13 items-center justify-center rounded-2xl bg-gradient-to-br from-[#22d3ee] to-[#7c1fd6] font-display text-lg font-bold text-white"
                    style="width:52px;height:52px;">
                    04
                </div>
                <h3 class="mb-2 font-display text-lg font-bold text-[#241a52]">Laporan &amp; Ekspor</h3>
                <p class="text-sm leading-relaxed text-[#5b5578]">
                    Ekspor rekap gaji bulanan ke Excel atau PDF untuk kebutuhan audit dan pelaporan.
                </p>
            </div>

            <div
                class="rounded-[22px] border border-[#ece7fb] bg-[#f4f1fb] p-8 transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#5b1fb8]/10">
                <div class="mb-5 flex h-13 w-13 items-center justify-center rounded-2xl bg-gradient-to-br from-[#22d3ee] to-[#7c1fd6] font-display text-lg font-bold text-white"
                    style="width:52px;height:52px;">
                    05
                </div>
                <h3 class="mb-2 font-display text-lg font-bold text-[#241a52]">Multi Role</h3>
                <p class="text-sm leading-relaxed text-[#5b5578]">
                    Akses berbeda untuk Admin, HR, dan Karyawan sesuai kebutuhan dan kewenangan masing-masing.
                </p>
            </div>

            <div
                class="rounded-[22px] border border-[#ece7fb] bg-[#f4f1fb] p-8 transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#5b1fb8]/10">
                <div class="mb-5 flex h-13 w-13 items-center justify-center rounded-2xl bg-gradient-to-br from-[#22d3ee] to-[#7c1fd6] font-display text-lg font-bold text-white"
                    style="width:52px;height:52px;">
                    06
                </div>
                <h3 class="mb-2 font-display text-lg font-bold text-[#241a52]">Data Aman</h3>
                <p class="text-sm leading-relaxed text-[#5b5578]">
                    Autentikasi memakai Laravel Breeze, memastikan data gaji karyawan tersimpan aman dan terenkripsi.
                </p>
            </div>
        </div>

        <div class="mt-16 text-center">
            @guest
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] px-9 py-4 font-display text-sm font-bold uppercase tracking-[0.14em] text-white shadow-lg shadow-[#e91e8c]/40 transition hover:-translate-y-0.5">
                    Mulai Sekarang
                </a>
            @endguest
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-[#140b3d] px-6 py-12 text-center text-sm text-white/55 sm:px-10 lg:px-14">
        <div class="mb-3 flex items-center justify-center gap-2 font-display font-bold tracking-wide text-white">

            <img src="{{ asset('images/logoHNRZikiPutih.png') }}" alt="HNRZ Salary Manager"
                class="h-[18px] w-[18px] object-contain">

            HNRZ Salary Manager
        </div>

        <p>&copy; {{ date('Y') }} {{ config('app.name', 'HNRZ Salary Manager') }}. Semua hak dilindungi.</p>

        @if (isset($laravelVersion) || app()->has('laravel'))
            <p class="mt-1 text-white/35">
                Laravel v{{ Illuminate\Foundation\Application::VERSION ?? app()->version() }}
            </p>
        @endif
    </footer>

</body>

</html>
