<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Riwayat Gaji
            </h2>
            <div class="flex gap-3">
                @can('delete-payroll-histories')
                    <a href="{{ route('admin.payroll-histories.trash') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                        Recycle Bin
                    </a>
                @endcan
                <a href="{{ route('admin.payroll-histories.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    + Tambah Riwayat
                </a>
            </div>
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

                    <livewire:admin.payroll-history-table />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
