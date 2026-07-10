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
                    <div><p class="text-sm text-gray-500">Email</p><p class="font-semibold">{{ $employee->email }}</p></div>
                    <div><p class="text-sm text-gray-500">Jabatan</p><p class="font-semibold">{{ $employee->position_name }}</p></div>
                    <div><p class="text-sm text-gray-500">Gaji</p><p class="font-semibold">{{ $employee->salary > 0 ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : 'No Position Assigned' }}</p></div>
                    <div>
                        <p class="text-sm text-gray-500">Bonus Tetap</p>
                        <div class="mt-1">
                            @forelse($employee->bonuses->where('jenis_bonus', 'Tetap') as $tetapBonus)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded mr-1 mb-1">
                                    {{ $tetapBonus->nama_bonus }} — {{ $tetapBonus->nominal_format }}
                                </span>
                            @empty
                                <p class="font-semibold text-gray-400">Belum ada bonus tetap</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bonus Variabel</p>
                        <div class="mt-1">
                            @php
                                $bonusVariabel = $employee->bonuses->firstWhere('jenis_bonus', 'Variabel');
                            @endphp

                            @if($bonusVariabel)
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                    {{ $bonusVariabel->nama_bonus }} — {{ $bonusVariabel->nominal_format }}
                                </span>
                            @else
                                <p class="font-semibold text-gray-400">Tidak ada bonus variabel</p>
                            @endif
                        </div>
                    </div>
                    <div><p class="text-sm text-gray-500">Alamat</p><p class="font-semibold">{{ $employee->alamat }}</p></div>
                    <div><p class="text-sm text-gray-500">Role</p><p class="font-semibold">{{ $employee->role }}</p></div>
                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Informasi Pembayaran Karyawan</h3>
                        <p class="text-sm text-gray-600 mb-4">Metode penggajian yang dipilih karyawan ditampilkan sebagai informasi read-only. Admin hanya dapat melihatnya.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Penggajian</label>
                                <input type="text" value="{{ optional($employee->payrollMethod)->name ? optional($employee->payrollMethod)->name . ' (' . optional($employee->payrollMethod)->type . ')' : 'Belum ditentukan' }}" class="block w-full border rounded px-3 py-2 bg-gray-100" readonly disabled>
                            </div>
                            @php($methodType = strtolower(optional($employee->payrollMethod)->type ?? ''))
                            @if(str_contains($methodType, 'bank'))
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                                    <input type="text" value="{{ old('nomor_rekening', $employee->nomor_rekening) }}" class="block w-full border rounded px-3 py-2 bg-gray-100" readonly disabled>
                                </div>
                            @elseif(str_contains($methodType, 'wallet') || str_contains($methodType, 'e-wallet') || str_contains($methodType, 'ewallet'))
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor E-Wallet</label>
                                    <input type="text" value="{{ old('nomor_e_wallet', $employee->nomor_e_wallet) }}" class="block w-full border rounded px-3 py-2 bg-gray-100" readonly disabled>
                                </div>
                            @else
                                <div class="md:col-span-2 rounded border border-gray-200 bg-white p-3 text-sm text-gray-600">
                                    Karyawan menggunakan metode Cash, sehingga tidak ada data rekening atau e-wallet yang perlu ditampilkan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('admin.employees.edit', $employee) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                    <a href="{{ route('admin.employees.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
