<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Metode Penggajian
        </h2>
        @can('create-payroll-methods')
            <a href="{{ route('admin.payroll-methods.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                + Tambah Metode
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
                                <th class="p-3">Kode</th>
                                <th class="p-3">Nama Metode</th>
                                <th class="p-3">Tipe</th>

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
                                    <td class="p-3 font-mono text-xs">{{ $method->code }}</td>
                                    <td class="p-3 font-semibold">{{ $method->name }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                        {{ $method->type }}
                                    </span>
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
                                                        <button type="submit" class="text-red-600 hover:underline">
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
