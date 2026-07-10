<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama atau NIK..."
               class="w-full md:col-span-2 border rounded px-3 py-2">

        {{-- <select wire:model.live="employee_id" class="border rounded px-3 py-2">
            <option value="">Semua karyawan</option>
            @foreach($employeeOptions as $employeeOption)
                <option value="{{ $employeeOption['id'] }}">{{ $employeeOption['label'] }}</option>
            @endforeach
        </select> --}}

        <select wire:model.live="jabatan" class="border rounded px-3 py-2">
            <option value="">Semua jabatan</option>
            @foreach($jabatans as $jabatanOption)
                <option value="{{ $jabatanOption }}">{{ $jabatanOption }}</option>
            @endforeach
        </select>

        <select wire:model.live="payment_status" class="border rounded px-3 py-2">
            <option value="">Semua status</option>
            <option value="Sudah Dibayar">Sudah Dibayar</option>
            <option value="Belum Dibayar">Belum Dibayar</option>
        </select>

        <select wire:model.live="payment_method" class="border rounded px-3 py-2">
            <option value="">Semua metode</option>
            @foreach($paymentMethods as $method)
                <option value="{{ $method }}">{{ $method }}</option>
            @endforeach
        </select>

        <input type="month" wire:model.live="payroll_period" class="border rounded px-3 py-2">
        <input type="date" wire:model.live="start_date" class="border rounded px-3 py-2">
        <input type="date" wire:model.live="end_date" class="border rounded px-3 py-2">
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
