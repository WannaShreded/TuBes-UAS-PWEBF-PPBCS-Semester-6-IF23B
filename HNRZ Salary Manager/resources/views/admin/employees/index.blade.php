<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Data Karyawan
            </h2>
            <a href="{{ route('admin.employees.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                + Tambah Karyawan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.employees.index') }}" class="mb-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama, NIK, email, jabatan..."
                               class="w-full md:w-80 border rounded px-3 py-2">
                        <button type="submit" class="ml-2 bg-gray-700 text-white px-4 py-2 rounded">Cari</button>
                    </form>

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
                                @forelse($employees as $employee)
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
                                                    <button
                                                        type="button"
                                                        data-type="danger"
                                                        data-title="Konfirmasi Hapus"
                                                        data-message="Anda akan menghapus karyawan '{{ $employee->nama_lengkap }}'. Tindakan ini tidak dapat dibatalkan."
                                                        data-confirm-text="Ya, Hapus"
                                                        data-action-url="{{ route('admin.employees.destroy', $employee) }}"
                                                        data-action-method="DELETE"
                                                        onclick="openConfirmFromEl(this)"
                                                        class="text-red-600 hover:underline"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-3 text-center text-gray-400">
                                            Belum ada data karyawan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
