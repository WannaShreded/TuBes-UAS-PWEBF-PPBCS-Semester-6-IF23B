<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HNRZ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|inter:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-[#241a52] bg-white">
        <div class="min-h-screen relative overflow-hidden cosmic-band flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            
            {{-- Decorative streak field --}}
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <svg viewBox="0 0 1400 620" preserveAspectRatio="xMidYMid slice" class="w-full h-full">
                    <defs>
                        <linearGradient id="streakPink" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#e91e8c"/>
                            <stop offset="100%" stop-color="#5b1fb8"/>
                        </linearGradient>
                        <linearGradient id="streakCyan" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#22d3ee"/>
                            <stop offset="100%" stop-color="#5b1fb8"/>
                        </linearGradient>
                        <radialGradient id="orbCyan" cx="35%" cy="30%" r="70%">
                            <stop offset="0%" stop-color="#a6f3ff"/>
                            <stop offset="55%" stop-color="#22d3ee"/>
                            <stop offset="100%" stop-color="#0e7fa8"/>
                        </radialGradient>
                    </defs>

                    <g stroke="#fbbf24" stroke-width="3" stroke-linecap="round" opacity="0.85">
                        <line x1="120" y1="560" x2="260" y2="380"/>
                        <line x1="340" y1="600" x2="480" y2="400"/>
                        <line x1="560" y1="580" x2="700" y2="360"/>
                        <line x1="780" y1="600" x2="920" y2="380"/>
                        <line x1="1000" y1="560" x2="1140" y2="360"/>
                        <line x1="1180" y1="600" x2="1320" y2="400"/>
                    </g>

                    <g stroke-linecap="round">
                        <line x1="60" y1="80" x2="200" y2="-40" stroke="url(#streakPink)" stroke-width="16" opacity="0.9"/>
                        <line x1="260" y1="140" x2="420" y2="-20" stroke="url(#streakCyan)" stroke-width="14" opacity="0.85"/>
                        <line x1="480" y1="200" x2="640" y2="40" stroke="url(#streakPink)" stroke-width="18" opacity="0.9"/>
                        <line x1="700" y1="120" x2="860" y2="-30" stroke="url(#streakCyan)" stroke-width="12" opacity="0.8"/>
                        <line x1="900" y1="220" x2="1060" y2="60" stroke="url(#streakPink)" stroke-width="16" opacity="0.85"/>
                        <line x1="1120" y1="140" x2="1280" y2="-10" stroke="url(#streakCyan)" stroke-width="14" opacity="0.85"/>
                    </g>

                    <g fill="none" stroke="#a78bfa" stroke-width="3" opacity="0.55" stroke-linecap="round">
                        <path d="M40 260 q20 -30 0 -60 t0 -60"/>
                        <path d="M1240 260 q20 -30 0 -60 t0 -60"/>
                        <path d="M1040 130 q20 -28 0 -56 t0 -56"/>
                    </g>

                    <g>
                        <circle cx="205" cy="170" r="34" fill="url(#orbCyan)" class="orb"/>
                        <circle cx="470" cy="260" r="16" fill="url(#orbCyan)" class="orb orb-b"/>
                        <circle cx="1000" cy="180" r="26" fill="url(#orbCyan)" class="orb"/>
                        <circle cx="1150" cy="290" r="12" fill="url(#orbCyan)" class="orb orb-c"/>
                        <circle cx="1250" cy="230" r="18" fill="url(#orbCyan)" class="orb orb-b"/>
                    </g>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-display text-2xl font-bold tracking-wide text-white mb-6">
                    <span class="relative inline-block h-6 w-6 rounded-full border-[3px] border-white">
                        <span class="absolute inset-[4px] rounded-full bg-white"></span>
                    </span>
                    HNRZ<span class="font-normal text-white/75">Salary Manager</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md px-8 py-8 bg-white/95 backdrop-blur-md border border-white/20 shadow-2xl shadow-[#140b3d]/50 overflow-hidden rounded-[22px] transition-all duration-300">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
