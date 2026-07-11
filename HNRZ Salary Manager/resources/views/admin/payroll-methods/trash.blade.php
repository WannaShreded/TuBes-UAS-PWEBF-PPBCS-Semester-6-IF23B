<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Recycle Bin - Metode Penggajian
        </h2>
        @can('delete-payroll-methods')
            <a href="{{ route('admin.payroll-methods.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                &larr; Kembali ke Metode Penggajian
            </a>
        @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
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
                                <th class="p-3">Tipe Metode</th>
                                <th class="p-3">Nama</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Deskripsi</th>
                                <th class="p-3">Dihapus Pada</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrollMethods as $index => $method)
                                <tr class="border-b">
                                    <td class="p-3">{{ $payrollMethods->firstItem() + $index }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                            {{ $method->type }}
                                        </span>
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
                                    <td class="p-3 text-gray-500">
                                        {{ $method->deleted_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @can('delete-payroll-methods')
                                                <button
                                                    type="button"
                                                    data-type="warning"
                                                    data-title="Konfirmasi Pulihkan"
                                                    data-message="Kembalikan metode penggajian '{{ $method->name }}' ke data utama?"
                                                    data-confirm-text="Ya, Pulihkan"
                                                    data-action-url="{{ route('admin.payroll-methods.restore', $method->id) }}"
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
                                                    data-message="Metode penggajian '{{ $method->name }}' akan dihapus permanen dan tidak dapat dikembalikan lagi. Lanjutkan?"
                                                    data-confirm-text="Ya, Hapus Permanen"
                                                    data-action-url="{{ route('admin.payroll-methods.force-delete', $method->id) }}"
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
                                    <td colspan="6" class="p-3 text-center text-gray-400">
                                        Recycle Bin metode penggajian kosong.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $payrollMethods->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
