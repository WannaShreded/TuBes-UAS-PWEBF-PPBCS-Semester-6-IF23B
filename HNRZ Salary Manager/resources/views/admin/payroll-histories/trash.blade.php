<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Recycle Bin - Riwayat Gaji
            </h2>

            @can('delete-payroll-histories')
                <a href="{{ route('admin.payroll-histories.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    &larr; Kembali ke Riwayat Gaji
                </a>
            @endcan
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
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <form method="GET" action="{{ route('admin.payroll-histories.trash') }}">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama atau NIK..."
                                   class="border rounded px-3 py-2 w-full md:w-80">
                        </form>
                    </div>

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Karyawan</th>
                                <th class="p-3">Jabatan</th>
                                <th class="p-3">Periode</th>
                                <th class="p-3">Total</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Dihapus Pada</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $index => $history)
                                <tr class="border-b">
                                    <td class="p-3">{{ $histories->firstItem() + $index }}</td>
                                    <td class="p-3 font-medium">{{ $history->employee->nama_lengkap ?? '-' }}</td>
                                    <td class="p-3">{{ $history->jabatan }}</td>
                                    <td class="p-3">{{ $history->payroll_period }}</td>
                                    <td class="p-3">Rp {{ number_format($history->total_dibayarkan, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <span @class([
                                            'px-2 py-1 rounded text-xs font-medium',
                                            'bg-green-100 text-green-800' => $history->payment_status === 'Sudah Dibayar',
                                            'bg-yellow-100 text-yellow-800' => $history->payment_status === 'Belum Dibayar',
                                        ])>{{ $history->payment_status }}</span>
                                    </td>
                                    <td class="p-3 text-gray-500">
                                        {{ $history->deleted_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @can('delete-payroll-histories')
                                                <button
                                                    type="button"
                                                    data-type="warning"
                                                    data-title="Konfirmasi Pulihkan"
                                                    data-message="Kembalikan riwayat gaji '{{ $history->employee->nama_lengkap ?? '-' }}' periode {{ $history->payroll_period }} ke data utama?"
                                                    data-confirm-text="Ya, Pulihkan"
                                                    data-action-url="{{ route('admin.payroll-histories.restore', $history->id) }}"
                                                    data-action-method="PATCH"
                                                    onclick="openConfirmFromEl(this)"
                                                    class="text-green-600 hover:underline"
                                                >
                                                    Restore
                                                </button>

                                                <button
                                                    type="button"
                                                    data-type="danger"
                                                    data-title="Konfirmasi Hapus Permanen"
                                                    data-message="Riwayat gaji ini akan dihapus permanen dan tidak dapat dikembalikan lagi. Lanjutkan?"
                                                    data-confirm-text="Ya, Hapus Permanen"
                                                    data-action-url="{{ route('admin.payroll-histories.force-delete', $history->id) }}"
                                                    data-action-method="DELETE"
                                                    onclick="openConfirmFromEl(this)"
                                                    class="text-red-600 hover:underline"
                                                >
                                                    Delete Permanently
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-3 text-center text-gray-400">
                                        Recycle Bin riwayat gaji kosong.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $histories->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
