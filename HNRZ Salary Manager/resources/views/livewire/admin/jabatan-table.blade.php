<div>
    <div class="mb-4">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama jabatan atau deskripsi..."
               class="w-full md:w-80 border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Nama Jabatan</th>
                    <th class="p-3">Gaji</th>
                    <th class="p-3">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $jabatan)
                    <tr class="border-b">
                        <td class="p-3">{{ $items->firstItem() + $index }}</td>
                        <td class="p-3 font-semibold">{{ $jabatan->name }}</td>
                        <td class="p-3">Rp {{ number_format($jabatan->salary, 0, ',', '.') }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-3">
                                @role('admin')
                                    <a href="{{ route('admin.jabatan.edit', $jabatan) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.jabatan.destroy', $jabatan) }}" method="POST" onsubmit="return confirm('Hapus jabatan {{ $jabatan->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
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
