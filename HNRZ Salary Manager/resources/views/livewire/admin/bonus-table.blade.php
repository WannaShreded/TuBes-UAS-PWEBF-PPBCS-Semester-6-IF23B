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
                    <th class="p-3">Deskripsi</th>
                    @canany(['edit-bonuses', 'delete-bonuses'])
                        <th class="p-3">Aksi</th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $bonus)
                    <tr class="border-b" wire:key="bonus-row-{{ $bonus->id }}">
                        <td class="p-3">{{ $items->firstItem() + $index }}</td>
                        <td class="p-3 font-medium">{{ $bonus->nama_bonus }}</td>
                        <td class="p-3">{{ $bonus->nominal_format }}</td>
                        <td class="p-3">
                            <span @class([
                                'px-2 py-1 rounded text-xs font-medium',
                                'bg-blue-100 text-blue-800'     => $bonus->jenis_bonus === 'Tetap',
                                'bg-orange-100 text-orange-800' => $bonus->jenis_bonus === 'Variabel',
                            ])>{{ $bonus->jenis_bonus }}</span>
                        </td>
                        <td class="p-3">{{ $bonus->periode_label }}</td>
                        <td class="p-3 max-w-sm whitespace-normal break-words">
                            {{ $bonus->deskripsi ?? '-' }}
                        </td>
                        @canany(['edit-bonuses', 'delete-bonuses'])
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @can('edit-bonuses')
                                        <a href="{{ route('admin.bonuses.edit', $bonus) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    @endcan

                                    @if($bonus->jenis_bonus === 'Tetap')
                                        @can('edit-bonuses')
                                            @if($bonus->employees_count > 0)
                                                <button
                                                    type="button"
                                                    wire:key="cancel-all-{{ $bonus->id }}-{{ $bonus->employees_count }}"
                                                    wire:click="confirmCancelAll({{ $bonus->id }}, '{{ addslashes($bonus->nama_bonus) }}')"
                                                    class="text-red-600 hover:underline"
                                                >
                                                    Batalkan ke Semua
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    wire:key="give-all-{{ $bonus->id }}-{{ $bonus->employees_count }}"
                                                    wire:click="confirmGiveToAll({{ $bonus->id }}, '{{ addslashes($bonus->nama_bonus) }}')"
                                                    class="text-purple-600 hover:underline"
                                                >
                                                    Berikan ke Semua
                                                </button>
                                            @endif
                                        @endcan
                                    @endif

                                    @can('delete-bonuses')
                                        <button
                                            type="button"
                                            wire:key="delete-{{ $bonus->id }}"
                                            wire:click="confirmDelete({{ $bonus->id }}, '{{ addslashes($bonus->nama_bonus) }}')"
                                            class="text-red-600 hover:underline"
                                        >
                                            Hapus
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-3 text-center text-gray-400">
                            Belum ada data bonus.
                        </td>
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
