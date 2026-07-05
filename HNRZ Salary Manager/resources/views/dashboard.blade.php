{{-- File: resources/views/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <p class="text-lg">
                        Selamat datang,
                        <strong>{{ Auth::user()->name }}</strong>!
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Role Anda:
                        <span class="font-semibold text-blue-600">
                            {{ Auth::user()->getRoleNames()->implode(', ') }}
                        </span>
                    </p>

                    @php($employeeProfile = Auth::user()->employee()->with(['jabatanRelation', 'payrollMethod'])->first())

                    @if($employeeProfile)
                        <div class="mt-6 p-4 bg-indigo-50 rounded-lg">
                            <h3 class="font-semibold text-indigo-800 mb-3">Informasi Payroll Saya</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div><span class="text-gray-500">ID Pekerja</span><div class="font-semibold">{{ $employeeProfile->id_pekerja }}</div></div>
                                <div><span class="text-gray-500">Nama Lengkap</span><div class="font-semibold">{{ $employeeProfile->nama_lengkap }}</div></div>
                                <div><span class="text-gray-500">Jabatan</span><div class="font-semibold">{{ $employeeProfile->jabatanRelation?->name ?? $employeeProfile->jabatan }}</div></div>
                                <div><span class="text-gray-500">Gaji</span><div class="font-semibold">Rp {{ number_format($employeeProfile->salary, 0, ',', '.') }}</div></div>
                                <div><span class="text-gray-500">Metode Penggajian</span><div class="font-semibold">{{ $employeeProfile->payrollMethod?->name ?? '-' }}</div></div>
                            </div>
                        </div>
                    @endif

                    @canany(['view-users', 'view-roles'])
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-blue-800 mb-3">Menu Admin</h3>
                            <div class="flex gap-3">

                                @can('view-users')
                                    <a href="{{ route('admin.users.index') }}"
                                    class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Kelola User
                                    </a>
                                @endcan

                                @can('view-roles')
                                    <a href="{{ route('admin.roles.index') }}"
                                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        Kelola Role
                                    </a>
                                @endcan

                                @can('view-roles')
                                    <a href="{{ route('admin.payroll-methods.index') }}"
                                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        Kelola Gaji
                                    </a>
                                @endcan

                                @can('view-roles')
                                    <a href="{{ route('admin.jabatan.index') }}"
                                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        Kelola Jabatan
                                    </a>
                                @endcan

                                @can('view-roles')
                                    <a href="{{ route('admin.employees.index') }}"
                                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        Kelola Karyawan
                                    </a>
                                @endcan

                            </div>
                        </div>
                    @endcanany

                    {{-- Pesan untuk Manager --}}
                    @role('manager')
                        <div class="mt-6 p-4 bg-green-50 rounded-lg">
                            <h3 class="font-semibold text-green-800 mb-2">
                                Informasi Manager
                            </h3>
                            <p class="text-green-700 text-sm">
                                Anda login sebagai Manager. Anda hanya dapat mengakses halaman dashboard.
                            </p>
                        </div>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
