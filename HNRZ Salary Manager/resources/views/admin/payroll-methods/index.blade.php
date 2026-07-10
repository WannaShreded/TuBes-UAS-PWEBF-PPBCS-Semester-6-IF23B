<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Metode Penggajian
        </h2>
        <div class="flex items-center gap-2">
            @can('delete-payroll-methods')
                <a href="{{ route('admin.payroll-methods.trash') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    Recycle Bin
                </a>
            @endcan
            @can('create-payroll-methods')
                <a href="{{ route('admin.payroll-methods.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    + Tambah Metode
                </a>
            @endcan
        </div>
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

                    <form method="GET" action="{{ route('admin.payroll-methods.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama, tipe, deskripsi"
                               class="border rounded px-3 py-2 md:col-span-2">
                        <select name="type" class="border rounded px-3 py-2">
                            <option value="">Semua tipe</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        <select name="is_active" class="border rounded px-3 py-2">
                            <option value="">Semua status</option>
                            <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                            <option value="0" @selected(request('is_active') === '0')>Nonaktif</option>
                        </select>
                        <div class="md:col-span-4 flex gap-2">
                            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded">Cari</button>
                            <a href="{{ route('admin.payroll-methods.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Reset</a>
                        </div>
                    </form>

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Metode</th>
                                <th class="p-3">Tipe</th>
                                <th class="p-3">Deskripsi</th>
                                <th class="p-3">Status</th>
                                @canany(['edit-payroll-methods', 'delete-payroll-methods'])
                                    <th class="p-3">Aksi</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrollMethods as $index => $method)
                                <tr class="border-b">
                                <td class="p-3">{{ $payrollMethods->firstItem() + $index }}</td>
                                <td class="p-3 font-semibold">{{ $method->name }}</td>                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                        {{ $method->type }}
                                    </span>
                                    </td>
                                    <td class="p-3 text-gray-600">
                                        {{ $method->description ?: '-' }}
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
                                                    <a href="{{ route('admin.payroll-methods.edit', $method) }}"
                                                    class="text-blue-600 hover:underline">Edit</a>
                                                @endcan

                                                @can('delete-payroll-methods')
                                                    <form action="{{ route('admin.payroll-methods.destroy', $method) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus metode penggajian {{ $method->name }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="button"
                                                            data-type="danger"
                                                            data-title="Konfirmasi Hapus"
                                                            data-message="Anda akan menghapus metode penggajian '{{ $method->name }}'. Tindakan ini tidak dapat dibatalkan."
                                                            data-confirm-text="Ya, Hapus"
                                                            data-action-url="{{ route('admin.payroll-methods.destroy', $method) }}"
                                                            data-action-method="DELETE"
                                                            onclick="openConfirmFromEl(this)"
                                                            class="text-red-600 hover:underline"
                                                        >
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-3 text-center text-gray-400">
                                        Belum ada metode penggajian.
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
