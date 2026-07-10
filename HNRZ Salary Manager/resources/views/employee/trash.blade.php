<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Recycle Bin - Karyawan
            </h2>
            <a href="{{ route('admin.employees.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                &larr; Kembali ke Data Karyawan
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
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.employees.trash') }}" class="mb-4">
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
                                    <th class="p-3">Email</th>
                                    <th class="p-3">Dihapus Pada</th>
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
                                        <td class="p-3">{{ $employee->email }}</td>
                                        <td class="p-3 text-gray-500">
                                            {{ $employee->deleted_at?->format('d M Y H:i') }}
                                        </td>
                                        <td class="p-3">
                                            <div class="flex items-center gap-3">
                                                <button
                                                    type="button"
                                                    data-type="warning"
                                                    data-title="Konfirmasi Pulihkan"
                                                    data-message="Kembalikan karyawan '{{ $employee->nama_lengkap }}' ke data utama?"
                                                    data-confirm-text="Ya, Pulihkan"
                                                    data-action-url="{{ route('admin.employees.restore', $employee->id) }}"
                                                    data-action-method="PATCH"
                                                    onclick="openConfirmFromEl(this)"
                                                    class="text-green-600 hover:underline"
                                                >
                                                    Restore
                                                </button>

                                                <button
                                                    type="button"
                                                    data-type="danger"
                                                    data-title="Konfirmasi Hapus Permanen"
                                                    data-message="Karyawan '{{ $employee->nama_lengkap }}' beserta akun user terkait akan dihapus permanen dan tidak dapat dikembalikan lagi. Lanjutkan?"
                                                    data-confirm-text="Ya, Hapus Permanen"
                                                    data-action-url="{{ route('admin.employees.force-delete', $employee->id) }}"
                                                    data-action-method="DELETE"
                                                    onclick="openConfirmFromEl(this)"
                                                    class="text-red-600 hover:underline"
                                                >
                                                    Delete Permanently
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-3 text-center text-gray-400">
                                            Recycle Bin karyawan kosong.
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
