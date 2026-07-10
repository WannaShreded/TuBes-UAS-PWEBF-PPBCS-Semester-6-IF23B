<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Bonus
            </h2>
            <div class="flex items-center gap-2">
                @can('delete-bonuses')
                    <a href="{{ route('admin.bonuses.trash') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                        Recycle Bin
                    </a>
                @endcan
                @can('create-bonuses')
                    <a href="{{ route('admin.bonuses.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                        + Tambah Bonus
                    </a>
                @endcan
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
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <livewire:admin.bonus-table />

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
