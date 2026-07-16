<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Gaji Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Statistik Ringkasan --}}
            <div class="grid gap-6 sm:grid-cols-3">
                <div class="rounded-[22px] border border-[#ece7fb] bg-white p-6 shadow-sm shadow-[#5b1fb8]/5 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-gradient-to-br from-[#7c1fd6] to-[#e91e8c] text-white shadow-md shadow-[#5b1fb8]/10">
                            <span class="font-bold text-lg">$</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#5b5578]">Total Gaji Diterima</p>
                            <h3 class="mt-1 text-2xl font-bold text-[#241a52] font-display">Rp {{ number_format($totalPayroll, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="rounded-[22px] border border-[#ece7fb] bg-white p-6 shadow-sm shadow-[#5b1fb8]/5 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-gradient-to-br from-[#22d3ee] to-[#7c1fd6] text-white shadow-md shadow-[#5b1fb8]/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#5b5578]">Rata-rata Gaji Bulanan</p>
                            <h3 class="mt-1 text-2xl font-bold text-[#241a52] font-display">Rp {{ number_format($averagePayroll, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="rounded-[22px] border border-[#ece7fb] bg-white p-6 shadow-sm shadow-[#5b1fb8]/5 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-[#5b1fb8]/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#5b5578]">Periode Terbayar</p>
                            <h3 class="mt-1 text-2xl font-bold text-[#241a52] font-display">{{ $histories->total() }} Periode</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Breakdown Gaji --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-bold text-[#241a52] font-display">Breakdown Pembayaran Bulanan</h3>

                    <x-table-wrapper>
                        <table class="app-table">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Gaji Pokok</th>
                                    <th>Bonus</th>
                                    <th>Total Dibayarkan</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Tanggal Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    <tr>
                                        <td class="font-semibold">{{ Carbon\Carbon::parse($history->payroll_period . '-01')->translatedFormat('F Y') }}</td>
                                        <td>Rp {{ number_format($history->gaji_pokok, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($history->bonus, 0, ',', '.') }}</td>
                                        <td class="font-bold text-[#7c1fd6]">Rp {{ number_format($history->total_dibayarkan, 0, ',', '.') }}</td>
                                        <td>{{ $history->payment_method }}</td>
                                        <td>
                                            <x-badge type="{{ $history->payment_status === 'Sudah Dibayar' ? 'success' : 'warning' }}">
                                                {{ $history->payment_status }}
                                            </x-badge>
                                        </td>
                                        <td>{{ $history->payment_date ? $history->payment_date->translatedFormat('d F Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-4 text-center text-gray-400">Belum ada riwayat penggajian untuk Anda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </x-table-wrapper>

                    <div class="mt-4">
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
