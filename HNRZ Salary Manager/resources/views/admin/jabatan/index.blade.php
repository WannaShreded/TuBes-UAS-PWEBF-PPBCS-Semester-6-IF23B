<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Data Jabatan
            </h2>

            @role('admin')
                <a href="{{ route('admin.jabatan.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    + Tambah Jabatan
                </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.jabatan.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama jabatan atau deskripsi"
                               class="border rounded px-3 py-2 md:col-span-2">
                        <input type="number" name="salary_min" value="{{ request('salary_min') }}"
                               placeholder="Gaji min"
                               class="border rounded px-3 py-2">
                        <input type="number" name="salary_max" value="{{ request('salary_max') }}"
                               placeholder="Gaji max"
                               class="border rounded px-3 py-2">
                        <div class="md:col-span-4 flex gap-2">
                            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded">Cari</button>
                            <a href="{{ route('admin.jabatan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Reset</a>
                        </div>
                    </form>

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Jabatan</th>
                                <th class="p-3">Gaji</th>
                                <th class="p-3">Deskripsi</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($jabatans as $index => $jabatan)
                                <tr class="border-b">
                                    <td class="p-3">
                                        {{ $jabatans->firstItem() + $index }}
                                    </td>

                                    <td class="p-3 font-semibold">
                                        {{ $jabatan->name }}
                                    </td>

                                    <td class="p-3">
                                        Rp {{ number_format($jabatan->salary, 0, ',', '.') }}
                                    </td>

                                    <td class="p-3 max-w-sm whitespace-normal break-words">
                                        {{ $jabatan->description ?? '-' }}
                                    </td>

                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @role('admin')
                                                <a href="{{ route('admin.jabatan.edit', $jabatan) }}"
                                                    class="text-blue-600 hover:underline">
                                                    Edit
                                                </a>

                                                <form action="{{ route('admin.jabatan.destroy', $jabatan) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus jabatan {{ $jabatan->name }}?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="button"
                                                        data-type="danger"
                                                        data-title="Konfirmasi Hapus"
                                                        data-message="Anda akan menghapus jabatan '{{ $jabatan->name }}'. Tindakan ini tidak dapat dibatalkan."
                                                        data-confirm-text="Ya, Hapus"
                                                        data-action-url="{{ route('admin.jabatan.destroy', $jabatan) }}"
                                                        data-action-method="DELETE"
                                                        onclick="openConfirmFromEl(this)"
                                                        class="text-red-600 hover:underline"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-3 text-center text-gray-400">
                                        Belum ada data jabatan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $jabatans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
