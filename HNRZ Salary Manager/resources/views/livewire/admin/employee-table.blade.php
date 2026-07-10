<div>
    <div class="mb-4">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama, NIK, email, jabatan..."
               class="w-full md:w-80 border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">NIK</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Jabatan</th>
                    <th class="p-3">Telepon</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Role</th>
                    <th class="p-3">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $employee)
                    <tr class="border-b">
                        <td class="p-3">{{ $employee->id_pekerja }}</td>
                        <td class="p-3">{{ $employee->nik }}</td>
                        <td class="p-3 font-semibold">{{ $employee->nama_lengkap }}</td>
                        <td class="p-3">{{ $employee->position_name }}</td>
                        <td class="p-3">{{ $employee->no_telepon }}</td>
                        <td class="p-3">{{ $employee->email }}</td>
                        <td class="p-3">{{ $employee->role }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.employees.show', $employee) }}" class="text-green-600 hover:underline">Detail</a>
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Hapus karyawan {{ $employee->nama_lengkap }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-3 text-center text-gray-400">Belum ada data karyawan.</td>
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
