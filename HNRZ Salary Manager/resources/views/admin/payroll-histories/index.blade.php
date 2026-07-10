<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Riwayat Pembayaran Gaji
            </h2>
            <a href="{{ route('admin.payroll-histories.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                + Tambah Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.payroll-histories.index') }}" class="mb-4 flex flex-col md:flex-row gap-3 items-start md:items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama/NIK"
                               class="border rounded px-3 py-2 w-full md:w-80">
                        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded">Cari</button>
                        <a href="{{ route('admin.payroll-histories.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Reset</a>
                    </form>

                    <div class="mb-4 text-sm text-gray-600">
                        Menampilkan riwayat pembayaran yang sudah dibayar dan memiliki tanggal pembayaran dalam 1 bulan terakhir.
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">Karyawan</th>
                                <th class="p-3">Jabatan</th>
                                <th class="p-3">Periode</th>
                                <th class="p-3">Gaji Pokok</th>
                                <th class="p-3">Bonus</th>
                                <th class="p-3">Total</th>
                                <th class="p-3">Metode</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($histories as $history)
                                <tr class="border-b">
                                    <td class="p-3">{{ $history->employee->nama_lengkap ?? '-' }}</td>
                                    <td class="p-3">{{ $history->jabatan }}</td>
                                    <td class="p-3">{{ $history->payroll_period }}</td>
                                    <td class="p-3">Rp {{ number_format($history->gaji_pokok, 0, ',', '.') }}</td>
                                    <td class="p-3">Rp {{ number_format($history->bonus, 0, ',', '.') }}</td>
                                    <td class="p-3 font-semibold">Rp {{ number_format($history->total_dibayarkan, 0, ',', '.') }}</td>
                                    <td class="p-3">{{ $history->payment_method }}</td>
                                    <td class="p-3">{{ $history->payment_status }}</td>
                                    <td class="p-3">{{ $history->payment_date ? $history->payment_date->format('d-m-Y') : '-' }}</td>
                                    <td class="p-3 flex gap-3">
                                        <a href="{{ route('admin.payroll-histories.edit', $history) }}" class="text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.payroll-histories.destroy', $history) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="p-3 text-center text-gray-400">Belum ada riwayat pembayaran dalam 1 bulan terakhir.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
