<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Informasi Jabatan Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Nama Jabatan</p>
                        <p class="font-semibold">{{ $employee->position_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Gaji</p>
                        <p class="font-semibold">{{ $employee->salary > 0 ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : 'No Position Assigned' }}</p>
                    </div>
                    @if($employee->jabatanRelation?->description)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Deskripsi Jabatan</p>
                            <p class="font-semibold">{{ $employee->jabatanRelation->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
