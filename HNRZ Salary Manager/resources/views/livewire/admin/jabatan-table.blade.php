<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama jabatan atau deskripsi..."
               class="w-full md:col-span-1 border rounded px-3 py-2">

        <input type="number"
               wire:model.live.debounce.300ms="salary_min"
               placeholder="Gaji min"
               class="border rounded px-3 py-2">

        <input type="number"
               wire:model.live.debounce.300ms="salary_max"
               placeholder="Gaji max"
               class="border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="relative transition-opacity duration-200">
        <div wire:loading.flex class="absolute inset-x-0 top-0 z-10 items-center gap-2 rounded-lg bg-white/90 px-3 py-2 text-sm font-medium text-slate-600 shadow-sm" role="status"><span class="h-3 w-3 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></span>Memperbarui data…</div>
        <div class="overflow-x-auto">
            <table class="app-table">
                <thead>
                <tr>
                    <th class="p-3">No</th>
                    <th><button wire:click="sortBy('name')">Nama Jabatan</button></th>
                    <th><button wire:click="sortBy('gaji_pokok')">Gaji</button></th>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $jabatan)
                    <tr class="border-b" wire:key="jabatan-row-{{ $jabatan->id }}">
                        <td class="p-3">{{ $items->firstItem() + $index }}</td>
                        <td class="p-3 font-semibold">{{ $jabatan->name }}</td>
                        <td class="p-3">Rp {{ number_format($jabatan->salary, 0, ',', '.') }}</td>
                        <td class="p-3 max-w-sm whitespace-normal break-words">
                            {{ $jabatan->description ?? '-' }}
                        </td>
                        <td class="p-3">
                            <div class="flex items-center gap-3">
                                @role('admin')
                                    <a href="{{ route('admin.jabatan.edit', $jabatan) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $jabatan->id }}, '{{ addslashes($jabatan->name) }}')"
                                        class="text-red-600 hover:underline"
                                    >
                                        Hapus
                                    </button>
                                @endrole
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-3 text-center text-gray-400">Belum ada data jabatan.</td>
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
