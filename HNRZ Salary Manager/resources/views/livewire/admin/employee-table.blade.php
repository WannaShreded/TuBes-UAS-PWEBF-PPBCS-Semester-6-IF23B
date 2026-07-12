<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama, NIK, email, jabatan..."
               class="w-full md:col-span-2 border rounded px-3 py-2">

        <select wire:model.live="role" class="border rounded px-3 py-2">
            <option value="">Semua role</option>
            @foreach($roles as $roleOption)
                <option value="{{ $roleOption }}">{{ $roleOption }}</option>
            @endforeach
        </select>

        <select wire:model.live="status" class="border rounded px-3 py-2">
            <option value="">Semua status</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
        </select>
    </div>

    <div class="mb-4">
        <select wire:model.live="jabatan" class="w-full md:w-80 border rounded px-3 py-2">
            <option value="">Semua jabatan</option>
            @foreach($jabatans as $jabatanOption)
                <option value="{{ $jabatanOption }}">{{ $jabatanOption }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading.class="opacity-60" class="relative transition-opacity duration-200">
        <div wire:loading.flex class="absolute inset-x-0 top-0 z-10 items-center gap-2 rounded-lg bg-white/90 px-3 py-2 text-sm font-medium text-slate-600 shadow-sm" role="status"><span class="h-3 w-3 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></span>Memperbarui data…</div>
        <div class="overflow-x-auto">
            <table class="app-table">
                <thead>
                <tr>
                    <th><button wire:click="sortBy('id_pekerja')">ID</button></th>
                    <th><button wire:click="sortBy('nik')">NIK</button></th>
                    <th><button wire:click="sortBy('nama_lengkap')">Nama</button></th>
                    <th class="p-3">Jabatan</th>
                    <th class="p-3">No Telepon</th>
                    <th><button wire:click="sortBy('email')">Email</button></th>
                    <th><button wire:click="sortBy('is_active')">Status</button></th>
                    <th class="p-3">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $employee)
                    <tr class="border-b" wire:key="employee-row-{{ $employee->id }}">
                        <td class="p-3">{{ $employee->id_pekerja }}</td>
                        <td class="p-3">{{ $employee->nik }}</td>
                        <td class="p-3 font-semibold">{{ $employee->nama_lengkap }}</td>
                        <td class="p-3">{{ $employee->position_name }}</td>
                        <td class="p-3">{{ $employee->no_telepon }}</td>
                        <td class="p-3">{{ $employee->email }}</td>
                        <td class="p-3">
                            @if($employee->is_active)
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.employees.show', $employee) }}"
                                class="text-green-600 hover:underline">Detail</a>
                                <a href="{{ route('admin.employees.edit', $employee) }}"
                                class="text-blue-600 hover:underline">Edit</a>
                                <button
                                    type="button"
                                    wire:click="confirmDelete({{ $employee->id }}, '{{ addslashes($employee->nama_lengkap) }}')"
                                    class="text-red-600 hover:underline"
                                >
                                    Hapus
                                </button>
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
