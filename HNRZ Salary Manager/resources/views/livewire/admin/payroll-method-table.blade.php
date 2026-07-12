<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama, tipe, atau deskripsi..."
               class="w-full md:col-span-2 border rounded px-3 py-2">

        <select wire:model.live="type" class="border rounded px-3 py-2">
            <option value="">Semua tipe</option>
            @foreach($types as $typeOption)
                <option value="{{ $typeOption }}">{{ $typeOption }}</option>
            @endforeach
        </select>

        <select wire:model.live="is_active" class="border rounded px-3 py-2">
            <option value="">Semua status</option>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>

    <div wire:loading.class="opacity-60" class="relative transition-opacity duration-200">
        <div wire:loading.flex class="absolute inset-x-0 top-0 z-10 items-center gap-2 rounded-lg bg-white/90 px-3 py-2 text-sm font-medium text-slate-600 shadow-sm" role="status"><span class="h-3 w-3 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></span>Memperbarui data…</div>
        <div class="overflow-x-auto">
            <table class="app-table">
                <thead>
                <tr>
                    <th class="p-3">No</th>
                    <th><button wire:click="sortBy('type')">Tipe Metode</button></th>
                    <th><button wire:click="sortBy('name')">Nama</button></th>
                    <th><button wire:click="sortBy('is_active')">Status</button></th>
                    <th class="p-3">Deskripsi</th>
                    @canany(['edit-payroll-methods', 'delete-payroll-methods'])
                        <th class="p-3">Aksi</th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $method)
                    <tr class="border-b" wire:key="payroll-method-row-{{ $method->id }}">
                        <td class="p-3">{{ $items->firstItem() + $index }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">{{ $method->type }}</span>
                        </td>
                        <td class="p-3 font-semibold">{{ $method->name }}</td>
                        <td class="p-3">
                            @if($method->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-gray-200 text-gray-600 rounded text-xs">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-3 max-w-sm whitespace-normal break-words">
                            {{ $method->description ?? '-' }}
                        </td>
                        @canany(['edit-payroll-methods', 'delete-payroll-methods'])
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @can('edit-payroll-methods')
                                        <a href="{{ route('admin.payroll-methods.edit', $method) }}" class="text-blue-600 hover:underline">Edit</a>
                                    @endcan
                                    @can('delete-payroll-methods')
                                        <button
                                            type="button"
                                            wire:click="confirmDelete({{ $method->id }}, '{{ addslashes($method->name) }}')"
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
                        <td colspan="5" class="p-3 text-center text-gray-400">Belum ada metode penggajian.</td>
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
