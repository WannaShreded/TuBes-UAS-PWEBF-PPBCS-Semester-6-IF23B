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

                    @php($employeeProfile = Auth::user()->employee()->with(['position', 'payrollMethod', 'bonuses'])->first())

                    @if($employeeProfile)
                        <div class="mt-6 p-4 bg-indigo-50 rounded-lg">
                            <h3 class="font-semibold text-indigo-800 mb-3">Informasi Profil Saya</h3>

                            <div class="grid grid-cols-1 gap-6 text-sm text-gray-700">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><span class="text-gray-500">ID Pekerja</span><div class="font-semibold">{{ $employeeProfile->id_pekerja }}</div></div>
                                    <div><span class="text-gray-500">NIK</span><div class="font-semibold">{{ $employeeProfile->nik }}</div></div>
                                    <div><span class="text-gray-500">Nama Lengkap</span><div class="font-semibold">{{ $employeeProfile->nama_lengkap }}</div></div>
                                    <div><span class="text-gray-500">Email</span><div class="font-semibold">{{ $employeeProfile->email }}</div></div>
                                    <div><span class="text-gray-500">No Telepon</span><div class="font-semibold">{{ $employeeProfile->no_telepon }}</div></div>
                                    <div><span class="text-gray-500">Role</span><div class="font-semibold">{{ $employeeProfile->role }}</div></div>
                                </div>

                                <div class="pt-4 border-t border-indigo-200">
                                    <h4 class="font-semibold text-indigo-800 mb-3">Informasi Pekerjaan</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div><span class="text-gray-500">Nama Lengkap</span><div class="font-semibold">{{ $employeeProfile->nama_lengkap }}</div></div>
                                        <div><span class="text-gray-500">Jabatan</span><div class="font-semibold">{{ $employeeProfile->position?->name ?? $employeeProfile->jabatan ?? 'No Position Assigned' }}</div></div>
                                        <div><span class="text-gray-500">Gaji</span><div class="font-semibold">{{ $employeeProfile->position?->salary ? 'Rp ' . number_format($employeeProfile->position->salary, 0, ',', '.') : 'No Position Assigned' }}</div></div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-indigo-200">
                                    <h4 class="font-semibold text-indigo-800 mb-3">Informasi Penggajian</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div><span class="text-gray-500">Metode Penggajian</span><div class="font-semibold">{{ $employeeProfile->payrollMethod?->name ?? 'Not Selected' }}</div></div>
                                        <div><span class="text-gray-500">Nama Bank</span><div class="font-semibold">{{ $employeeProfile->nama_bank ?: 'No Bank Information' }}</div></div>
                                        <div><span class="text-gray-500">Nomor Rekening</span><div class="font-semibold">{{ $employeeProfile->nomor_rekening ?: 'No Bank Information' }}</div></div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-indigo-200">
                                    <h4 class="font-semibold text-indigo-800 mb-3">Bonus</h4>
                                    @if($employeeProfile->bonuses->isNotEmpty())
                                        <ul class="list-disc pl-5 text-gray-700">
                                            @foreach($employeeProfile->bonuses as $bonus)
                                                <li>
                                                    <span class="font-semibold text-gray-900">{{ $bonus->nama_bonus }}</span>
                                                    <span class="text-gray-600">({{ $bonus->jenis_bonus }} - Rp {{ number_format($bonus->nominal_bonus, 0, ',', '.') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="font-semibold text-gray-900">No Bonuses Assigned</div>
                                    @endif
                                </div>

                                <div class="pt-4 border-t border-indigo-200">
                                    <h4 class="font-semibold text-indigo-800 mb-3">Alamat</h4>
                                    <div class="font-semibold">{{ $employeeProfile->alamat ?: 'Not Provided' }}</div>
                                </div>
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
                                    <a href="{{ route('admin.jabatan.index') }}"
                                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        Kelola Jabatan
                                    </a>
                                @endcan

                                @can('view-bonuses')
                                    <a href="{{ route('admin.bonuses.index') }}"
                                    class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Kelola Bonus
                                    </a>
                                @endcan

                                @can('view-roles')
                                    <a href="{{ route('admin.payroll-methods.index') }}"
                                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        Kelola Gaji
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

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
