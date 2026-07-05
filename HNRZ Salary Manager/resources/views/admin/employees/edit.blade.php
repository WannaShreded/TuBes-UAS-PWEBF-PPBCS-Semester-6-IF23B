<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Karyawan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                            <input type="text" name="nik" value="{{ old('nik', $employee->nik) }}" class="block w-full border rounded px-3 py-2">
                            @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $employee->nama_lengkap) }}" class="block w-full border rounded px-3 py-2">
                            @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                            <input type="text" name="no_telepon" value="{{ old('no_telepon', $employee->no_telepon) }}" class="block w-full border rounded px-3 py-2">
                            @error('no_telepon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                            <input type="text" name="nama_bank" value="{{ old('nama_bank', $employee->nama_bank) }}" class="block w-full border rounded px-3 py-2">
                            @error('nama_bank')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $employee->nomor_rekening) }}" class="block w-full border rounded px-3 py-2">
                            @error('nomor_rekening')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="block w-full border rounded px-3 py-2">
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <select name="jabatan" id="jabatan" class="block w-full border rounded px-3 py-2">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatans as $jabatan)
                                    <option value="{{ $jabatan->name }}" data-salary="{{ $jabatan->salary }}" {{ old('jabatan', $employee->jabatan) == $jabatan->name ? 'selected' : '' }}>
                                        {{ $jabatan->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jabatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gaji</label>
                            <input type="text" id="gaji" readonly
                                   class="block w-full border rounded px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed"
                                   placeholder="Otomatis terisi setelah memilih jabatan">
                            <p class="text-xs text-gray-400 mt-1">Gaji mengikuti nominal yang diatur pada data Jabatan.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="alamat" rows="3" class="block w-full border rounded px-3 py-2">{{ old('alamat', $employee->alamat) }}</textarea>
                            @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bonus Variabel</label>
                            <select name="bonus_variabel_id" class="block w-full border rounded px-3 py-2">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($variableBonuses as $variableBonus)
                                    <option value="{{ $variableBonus->id }}"
                                        {{ old('bonus_variabel_id', $currentBonusVariabelId) == $variableBonus->id ? 'selected' : '' }}>
                                        {{ $variableBonus->nama_bonus }} ({{ $variableBonus->nominal_format }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bonus_variabel_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-400 mt-1">Pilih bonus variabel khusus untuk karyawan ini (opsional).</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" class="block w-full border rounded px-3 py-2">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="karyawan" {{ old('role', $employee->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            </select>
                            @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (opsional)</label>
                            <input type="password" name="password" class="block w-full border rounded px-3 py-2">
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="block w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Perbarui</button>
                        <a href="{{ route('admin.employees.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jabatanSelect = document.getElementById('jabatan');
            const gajiInput = document.getElementById('gaji');

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(number);
            }

            function updateGaji() {
                const selectedOption = jabatanSelect.options[jabatanSelect.selectedIndex];
                const salary = selectedOption ? selectedOption.getAttribute('data-salary') : null;
                gajiInput.value = salary ? formatRupiah(salary) : '';
            }

            jabatanSelect.addEventListener('change', updateGaji);
            updateGaji();
        });
    </script>
</x-app-layout>
