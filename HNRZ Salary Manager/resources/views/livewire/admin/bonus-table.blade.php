<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari bonus, jenis, atau keterangan..."
               class="w-full md:col-span-2 border rounded px-3 py-2">

        <select wire:model.live="jenis_bonus" class="border rounded px-3 py-2">
            <option value="">Semua jenis</option>
            <option value="Tetap">Tetap</option>
            <option value="Variabel">Variabel</option>
        </select>

        <input type="month"
               wire:model.live="periode_bonus"
               class="border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Nama Bonus</th>
                    <th class="p-3">Nominal</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Periode</th>
                    <th class="p-3">Keterangan</th>
                    @canany(['edit-bonuses', 'delete-bonuses'])
                        <th class="p-3">Aksi</th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $bonus)
                    <tr class="border-b">
                        <td class="p-3">{{ $items->firstItem() + $index }}</td>
                        <td class="p-3 font-medium">{{ $bonus->nama_bonus }}</td>
                        <td class="p-3">{{ $bonus->nominal_format }}</td>
                        <td class="p-3">
                            <span @class([
                                'px-2 py-1 rounded text-xs font-medium',
                                'bg-blue-100 text-blue-800' => $bonus->jenis_bonus === 'Tetap',
                                'bg-orange-100 text-orange-800' => $bonus->jenis_bonus === 'Variabel',
                            ])>{{ $bonus->jenis_bonus }}</span>
                        </td>
                        <td class="p-3">{{ $bonus->periode_label }}</td>
                        <td class="p-3 text-gray-500">{{ $bonus->keterangan ?? '-' }}</td>
                        @canany(['edit-bonuses', 'delete-bonuses'])
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @can('edit-bonuses')
                                        <a href="{{ route('admin.bonuses.edit', $bonus) }}" class="text-blue-600 hover:underline">Edit</a>
                                    @endcan
                                    @if($bonus->jenis_bonus === 'Tetap')
                                        @can('edit-bonuses')
                                            <form action="{{ route('admin.bonuses.give-to-all', $bonus) }}" method="POST" onsubmit="return confirm('Berikan bonus &quot;{{ $bonus->nama_bonus }}&quot; ke SEMUA karyawan?')">
                                                @csrf
                                                <button type="submit" class="text-purple-600 hover:underline">Berikan ke Semua</button>
                                            </form>
                                        @endcan
                                    @endif
                                    @can('delete-bonuses')
                                        <form action="{{ route('admin.bonuses.destroy', $bonus) }}" method="POST" onsubmit="return confirm('Hapus bonus ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-3 text-center text-gray-400">Belum ada data bonus.</td>
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
