<div>
    <div class="mb-4">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama, tipe, atau deskripsi..."
               class="w-full md:w-80 border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Nama Metode</th>
                    <th class="p-3">Tipe</th>
                    <th class="p-3">Status</th>
                    @canany(['edit-payroll-methods', 'delete-payroll-methods'])
                        <th class="p-3">Aksi</th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $method)
                    <tr class="border-b">
                        <td class="p-3">{{ $items->firstItem() + $index }}</td>
                        <td class="p-3 font-semibold">{{ $method->name }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">{{ $method->type }}</span>
                        </td>
                        <td class="p-3">
                            @if($method->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-gray-200 text-gray-600 rounded text-xs">Nonaktif</span>
                            @endif
                        </td>
                        @canany(['edit-payroll-methods', 'delete-payroll-methods'])
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @can('edit-payroll-methods')
                                        <a href="{{ route('admin.payroll-methods.edit', $method) }}" class="text-blue-600 hover:underline">Edit</a>
                                    @endcan
                                    @can('delete-payroll-methods')
                                        <form action="{{ route('admin.payroll-methods.destroy', $method) }}" method="POST" onsubmit="return confirm('Hapus metode penggajian {{ $method->name }}?')">
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
