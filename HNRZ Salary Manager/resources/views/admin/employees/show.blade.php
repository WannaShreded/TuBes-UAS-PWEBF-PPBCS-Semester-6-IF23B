<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Karyawan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><p class="text-sm text-gray-500">ID Pekerja</p><p class="font-semibold">{{ $employee->id_pekerja }}</p></div>
                    <div><p class="text-sm text-gray-500">NIK</p><p class="font-semibold">{{ $employee->nik }}</p></div>
                    <div><p class="text-sm text-gray-500">Nama Lengkap</p><p class="font-semibold">{{ $employee->nama_lengkap }}</p></div>
                    <div><p class="text-sm text-gray-500">No Telepon</p><p class="font-semibold">{{ $employee->no_telepon }}</p></div>
                    <div><p class="text-sm text-gray-500">Nama Bank</p><p class="font-semibold">{{ $employee->nama_bank ?: '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Nomor Rekening</p><p class="font-semibold">{{ $employee->nomor_rekening ?: '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Nomor E-Wallet</p><p class="font-semibold">{{ $employee->nomor_e_wallet ?: '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Email</p><p class="font-semibold">{{ $employee->email }}</p></div>
                    <div><p class="text-sm text-gray-500">Jabatan</p><p class="font-semibold">{{ $employee->position_name }}</p></div>
                    <div><p class="text-sm text-gray-500">Gaji</p><p class="font-semibold">{{ $employee->salary > 0 ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : 'No Position Assigned' }}</p></div>
                    <div><p class="text-sm text-gray-500">Metode Penggajian</p><p class="font-semibold">{{ $employee->payrollMethod?->name ?? '-' }}</p></div>
                    <div class="md:col-span-2"><p class="text-sm text-gray-500">Alamat</p><p class="font-semibold">{{ $employee->alamat }}</p></div>
                    <div><p class="text-sm text-gray-500">Role</p><p class="font-semibold">{{ $employee->role }}</p></div>
                </div>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('admin.employees.edit', $employee) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                    <a href="{{ route('admin.employees.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
