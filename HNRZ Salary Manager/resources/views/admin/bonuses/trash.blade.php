<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Recycle Bin - Bonus
            </h2>

            @can('delete-bonuses')
                <a href="{{ route('admin.bonuses.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    &larr; Kembali ke Kelola Bonus
                </a>
            @endcan
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

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Bonus</th>
                                <th class="p-3">Nominal</th>
                                <th class="p-3">Jenis</th>
                                <th class="p-3">Periode</th>
                                <th class="p-3">Dihapus Pada</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bonuses as $index => $bonus)
                                <tr class="border-b">
                                    <td class="p-3">{{ $bonuses->firstItem() + $index }}</td>
                                    <td class="p-3 font-medium">{{ $bonus->nama_bonus }}</td>
                                    <td class="p-3">{{ $bonus->nominal_format }}</td>
                                    <td class="p-3">
                                        <span @class([
                                            'px-2 py-1 rounded text-xs font-medium',
                                            'bg-blue-100 text-blue-800'  => $bonus->jenis_bonus === 'Tetap',
                                            'bg-orange-100 text-orange-800' => $bonus->jenis_bonus === 'Variabel',
                                        ])>
                                            {{ $bonus->jenis_bonus }}
                                        </span>
                                    </td>
                                    <td class="p-3">{{ $bonus->periode_label }}</td>
                                    <td class="p-3 text-gray-500">
                                        {{ $bonus->deleted_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @can('delete-bonuses')
                                                <button
                                                    type="button"
                                                    data-type="warning"
                                                    data-title="Konfirmasi Pulihkan"
                                                    data-message="Kembalikan bonus '{{ $bonus->nama_bonus }}' ke data utama?"
                                                    data-confirm-text="Ya, Pulihkan"
                                                    data-action-url="{{ route('admin.bonuses.restore', $bonus->id) }}"
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
                                                    data-message="Bonus '{{ $bonus->nama_bonus }}' akan dihapus permanen dan tidak dapat dikembalikan lagi. Lanjutkan?"
                                                    data-confirm-text="Ya, Hapus Permanen"
                                                    data-action-url="{{ route('admin.bonuses.force-delete', $bonus->id) }}"
                                                    data-action-method="DELETE"
                                                    onclick="openConfirmFromEl(this)"
                                                    class="text-red-600 hover:underline"
                                                >
                                                    Delete Permanently
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-3 text-center text-gray-400">
                                        Recycle Bin bonus kosong.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $bonuses->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
