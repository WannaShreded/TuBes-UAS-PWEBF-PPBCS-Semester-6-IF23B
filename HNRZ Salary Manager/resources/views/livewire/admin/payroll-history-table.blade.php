<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama atau NIK..."
               class="w-full md:col-span-2 border rounded px-3 py-2">

        <select wire:model.live="payment_status" class="border rounded px-3 py-2">
            <option value="">Semua status</option>
            <option value="Sudah Dibayar">Sudah Dibayar</option>
            <option value="Belum Dibayar">Belum Dibayar</option>
        </select>

        <input type="month"
               wire:model.live="payroll_period"
               class="border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
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
                @forelse($items as $history)
                    <tr class="border-b" wire:key="payroll-history-row-{{ $history->id }}">
                        <td class="p-3">{{ $history->employee->nama_lengkap ?? '-' }}</td>
                        <td class="p-3">{{ $history->jabatan }}</td>
                        <td class="p-3">{{ $history->payroll_period }}</td>
                        <td class="p-3">Rp {{ number_format($history->gaji_pokok, 0, ',', '.') }}</td>
                        <td class="p-3">Rp {{ number_format($history->bonus, 0, ',', '.') }}</td>
                        <td class="p-3 font-semibold">Rp {{ number_format($history->total_dibayarkan, 0, ',', '.') }}</td>
                        <td class="p-3">{{ $history->payment_method }}</td>
                        <td class="p-3">
                            <span @class([
                                'px-2 py-1 rounded text-xs font-medium',
                                'bg-green-100 text-green-800' => $history->payment_status === 'Sudah Dibayar',
                                'bg-yellow-100 text-yellow-800' => $history->payment_status === 'Belum Dibayar',
                            ])>{{ $history->payment_status }}</span>
                        </td>
                        <td class="p-3">{{ $history->payment_date ? $history->payment_date->format('d-m-Y') : '-' }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.payroll-histories.edit', $history) }}"
                            class="text-blue-600 hover:underline">Edit</a>
                            <button
                                type="button"
                                wire:click="confirmDelete({{ $history->id }}, 'riwayat gaji ini')"
                                class="text-red-600 hover:underline"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr wire:key="payroll-history-empty">
                        <td colspan="10" class="p-3 text-center text-gray-400">Belum ada riwayat pembayaran.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
