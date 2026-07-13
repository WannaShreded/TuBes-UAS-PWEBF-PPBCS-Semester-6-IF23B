@php
    $adminGroups = [
        [
            'label' => 'Reports',
            'items' => [
                [
                    'label' => 'Statistik',
                    'route' => 'admin.statistics.index',
                    'active' => 'admin.statistics.*',
                    'icon' => 'M4 19V5m4 14v-7m4 7V8m4 11V4',
                ],
            ],
        ],
        [
            'label' => 'Payroll',
            'items' => [
                [
                    'label' => 'Riwayat Gaji',
                    'route' => 'admin.payroll-histories.index',
                    'active' => 'admin.payroll-histories.*',
                    'icon' => 'M12 8c-2.2 0-4 1.4-4 3s1.8 3 4 3 4-1.4 4-3-1.8-3-4-3Zm0 0V5m0 9v5M5 11h3m8 0h3',
                ],
                [
                    'label' => 'Metode Gaji',
                    'route' => 'admin.payroll-methods.index',
                    'active' => 'admin.payroll-methods.*',
                    'icon' =>
                        'M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9ZM7 10h10M7 14h4',
                ],
                [
                    'label' => 'Bonus',
                    'route' => 'admin.bonuses.index',
                    'active' => 'admin.bonuses.*',
                    'icon' => 'M12 8v8m4-4H8m8 8H8M5 5h14',
                ],
            ],
        ],
        [
            'label' => 'Employees',
            'items' => [
                [
                    'label' => 'Karyawan',
                    'route' => 'admin.employees.index',
                    'active' => 'admin.employees.*',
                    'icon' =>
                        'M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m14-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM21 21v-2a4 4 0 0 0-3-3.87',
                ],
                [
                    'label' => 'Jabatan',
                    'route' => 'admin.jabatan.index',
                    'active' => 'admin.jabatan.*',
                    'icon' => 'M4 5h16v14H4zM8 9h8M8 13h6',
                ],
            ],
        ],
        [
            'label' => 'Settings',
            'items' => [
                [
                    'label' => 'Pengguna',
                    'route' => 'admin.users.index',
                    'active' => 'admin.users.*',
                    'icon' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m12-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8',
                ],
                [
                    'label' => 'Role & Akses',
                    'route' => 'admin.roles.index',
                    'active' => 'admin.roles.*',
                    'icon' =>
                        'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm7.4-3.5a7.3 7.3 0 0 0-.1-1l2-1.5-2-3.5-2.3.9a7.5 7.5 0 0 0-1.7-1L15 3h-4l-.3 2.9a7.5 7.5 0 0 0-1.7 1L6.7 6 4.7 9.5l2 1.5a7.3 7.3 0 0 0 0 2l-2 1.5L6.7 18l2.3-.9a7.5 7.5 0 0 0 1.7 1L11 21h4l.3-2.9a7.5 7.5 0 0 0 1.7-1l2.3.9 2-3.5-2-1.5c.1-.3.1-.7.1-1Z',
                ],
            ],
        ],
    ];
@endphp

<div x-data="{ open: false, collapsed: false }" @keydown.escape.window="open = false">
    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/60 lg:hidden"
        @click="open = false"></div>

    {{-- Sidebar Container --}}
    <aside :class="{ '-translate-x-full': !open, 'w-20': collapsed, 'w-72': !collapsed }"
        class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-[#5b1fb8]/20 bg-gradient-to-b from-[#140b3d] to-[#1c0f4d] text-white transition-[width,transform] duration-200 ease-in-out lg:translate-x-0">

        {{-- Logo Header --}}
        <div class="flex h-16 items-center border-b border-[#5b1fb8]/20 px-4"
            :class="collapsed ? 'justify-center' : 'justify-between'">

            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3 font-bold text-white group"
                aria-label="Dashboard">

                <span class="flex h-8 w-8 shrink-0 items-center justify-center">
                    <img src="{{ asset('images/logoHNRZikiPutih.png') }}" alt="HNRZ Salary Manager"
                        class="h-8 w-8 object-contain">
                </span>

                <span x-show="!collapsed" x-transition.opacity
                    class="truncate font-display tracking-wide text-white leading-tight">
                    HNRZ
                    <span class="block text-xs font-normal text-white/60">
                        Salary Manager
                    </span>
                </span>

            </a>

            <button type="button" @click="collapsed = !collapsed"
                class="hidden rounded-lg p-2 text-white/55 hover:bg-[#5b1fb8]/20 hover:text-white transition-colors lg:block"
                :aria-label="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'">

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"
                        :class="collapsed ? '' : 'rotate-180'">
                    </path>
                </svg>

            </button>
        </div>

        {{-- Nav Links --}}
        <nav class="flex-1 overflow-y-auto p-3 space-y-1" aria-label="Navigasi utama">
            <a href="{{ route('dashboard') }}"
                class="mb-3 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] text-white shadow-lg shadow-[#e91e8c]/25' : 'text-white/70 hover:bg-[#5b1fb8]/20 hover:text-white' }}"
                :title="collapsed ? 'Dashboard' : ''">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12 12 3l9 9v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" />
                </svg>
                <span x-show="!collapsed" x-transition.opacity>Dashboard</span>
            </a>

            @role('admin')
                @foreach ($adminGroups as $group)
                    <section class="mb-4">
                        <p x-show="!collapsed" x-transition.opacity
                            class="mb-1.5 px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-[#e91e8c]/80">
                            {{ $group['label'] }}</p>
                        @foreach ($group['items'] as $item)
                            <a href="{{ route($item['route']) }}"
                                class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs($item['active']) ? 'bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] text-white shadow-lg shadow-[#e91e8c]/25 font-semibold' : 'text-white/70 hover:bg-[#5b1fb8]/20 hover:text-white' }}"
                                :title="collapsed ? '{{ $item['label'] }}' : ''">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="{{ $item['icon'] }}" />
                                </svg>
                                <span x-show="!collapsed" x-transition.opacity>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </section>
                @endforeach
            @else
                <p x-show="!collapsed" x-transition.opacity
                    class="mb-1.5 px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-[#e91e8c]/80">Employee</p>
                <a href="{{ route('employee.position') }}"
                    class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('employee.position') ? 'bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] text-white shadow-lg shadow-[#e91e8c]/25' : 'text-white/70 hover:bg-[#5b1fb8]/20 hover:text-white' }}"
                    :title="collapsed ? 'Jabatan' : ''">
                    <span class="h-5 w-5 shrink-0 flex items-center justify-center font-bold text-xs">⌘</span>
                    <span x-show="!collapsed" x-transition.opacity>Jabatan</span>
                </a>
                <a href="{{ route('employee.payroll-methods.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('employee.payroll-methods.*') ? 'bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] text-white shadow-lg shadow-[#e91e8c]/25' : 'text-white/70 hover:bg-[#5b1fb8]/20 hover:text-white' }}"
                    :title="collapsed ? 'Metode Gaji' : ''">
                    <span class="h-5 w-5 shrink-0 flex items-center justify-center font-bold text-xs">$</span>
                    <span x-show="!collapsed" x-transition.opacity>Metode Gaji</span>
                </a>
            @endrole
        </nav>

        {{-- Footer Profile & Logout --}}
        <div class="border-t border-[#5b1fb8]/20 p-3 space-y-1" :class="collapsed ? 'px-2' : ''">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-white/80 hover:bg-[#5b1fb8]/20 hover:text-white transition-colors"
                :title="collapsed ? 'Profil' : ''">
                <span
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#7c1fd6]/40 to-[#e91e8c]/40 border border-[#7c1fd6]/30 text-xs font-bold text-white shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <span x-show="!collapsed" x-transition.opacity
                    class="min-w-0 truncate font-medium">{{ Auth::user()->name }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-white/70 hover:bg-rose-500/20 hover:text-rose-200 transition-colors"
                    :title="collapsed ? 'Keluar' : ''">
                    <svg class="h-5 w-5 shrink-0 text-white/60 group-hover:text-white" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 17l5-5-5-5m5 5H3m12-7h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile Open Button --}}
    <button type="button" @click="open = true"
        class="fixed left-4 top-4 z-30 rounded-xl border border-[#5b1fb8]/20 bg-[#140b3d] p-2 text-white hover:bg-[#5b1fb8]/25 shadow-lg lg:hidden transition-colors"
        aria-label="Buka navigasi">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>
