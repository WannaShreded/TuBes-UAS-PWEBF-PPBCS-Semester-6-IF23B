<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Karyawan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.employees.update', $employee) }}" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                ID Pekerja
                            </label>
                            <input
                                type="text"
                                value="{{ $employee->id_pekerja }}"
                                readonly
                                class="block w-full border rounded px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed"
                            >
                        </div>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bonus Tetap</label>
                            <div class="block w-full border rounded px-3 py-2 space-y-2 max-h-40 overflow-y-auto">
                                @forelse($tetapBonuses as $tetapBonus)
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox"
                                               name="bonus_tetap_ids[]"
                                               value="{{ $tetapBonus->id }}"
                                               {{ in_array($tetapBonus->id, old('bonus_tetap_ids', $currentTetapBonusIds)) ? 'checked' : '' }}
                                               class="rounded border-gray-300">
                                        <span>{{ $tetapBonus->nama_bonus }} — {{ $tetapBonus->nominal_format }}</span>
                                    </label>
                                @empty
                                    <span class="text-gray-400 text-sm">Belum ada bonus tetap yang tersedia</span>
                                @endforelse
                            </div>
                            @error('bonus_tetap_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="alamat" rows="3" class="block w-full border rounded px-3 py-2">{{ old('alamat', $employee->alamat) }}</textarea>
                            @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                    <br>
                    <div class="flex gap-3">
                        <button
                            type="button"
                            data-type="warning"
                            data-title="Konfirmasi Simpan"
                            data-message="Anda akan menyimpan perubahan karyawan '{{ $employee->nama_lengkap }}'. Lanjutkan?"
                            data-confirm-text="Ya, Simpan"
                            data-form-id="editForm"
                            onclick="openConfirmFromEl(this)"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.employees.index') }}"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                            Batal
                        </a>
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
