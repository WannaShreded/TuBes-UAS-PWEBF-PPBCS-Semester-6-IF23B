<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Recycle Bin - Jabatan
            </h2>

            @role('admin')
                <a href="{{ route('admin.jabatan.index') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    &larr; Kembali ke Data Jabatan
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
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Jabatan</th>
                                <th class="p-3">Gaji</th>
                                <th class="p-3">Deskripsi</th>
                                <th class="p-3">Dihapus Pada</th>
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

                                    <td class="p-3 text-gray-500">
                                        {{ $jabatan->deleted_at?->format('d M Y H:i') }}
                                    </td>

                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @role('admin')
                                                <button
                                                    type="button"
                                                    data-type="warning"
                                                    data-title="Konfirmasi Pulihkan"
                                                    data-message="Kembalikan jabatan '{{ $jabatan->name }}' ke data utama?"
                                                    data-confirm-text="Ya, Pulihkan"
                                                    data-action-url="{{ route('admin.jabatan.restore', $jabatan->id) }}"
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
                                                    data-message="Jabatan '{{ $jabatan->name }}' akan dihapus permanen dan tidak dapat dikembalikan lagi. Lanjutkan?"
                                                    data-confirm-text="Ya, Hapus Permanen"
                                                    data-action-url="{{ route('admin.jabatan.force-delete', $jabatan->id) }}"
                                                    data-action-method="DELETE"
                                                    onclick="openConfirmFromEl(this)"
                                                    class="text-red-600 hover:underline"
                                                >
                                                    Delete Permanently
                                                </button>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-3 text-center text-gray-400">
                                        Recycle Bin jabatan kosong.
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
