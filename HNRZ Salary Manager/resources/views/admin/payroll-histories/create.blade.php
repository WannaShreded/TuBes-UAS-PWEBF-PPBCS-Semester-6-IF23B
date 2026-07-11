<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Riwayat Pembayaran Gaji
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.payroll-histories.store') }}">
                    @csrf

                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-gray-700">Karyawan</label>
                            <div class="flex gap-3 text-xs">
                                <button type="button" onclick="toggleAllEmployees(true)" class="text-blue-600 hover:underline">
                                    Pilih Semua
                                </button>
                                <button type="button" onclick="toggleAllEmployees(false)" class="text-gray-500 hover:underline">
                                    Batalkan Semua
                                </button>
                            </div>
                        </div>

                        <div class="block w-full border rounded px-3 py-2 space-y-2 max-h-60 overflow-y-auto">
                            @forelse($employees as $employee)
                                <label class="flex items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        name="employee_ids[]"
                                        value="{{ $employee->id }}"
                                        class="employee-checkbox rounded border-gray-300"
                                        {{ in_array($employee->id, old('employee_ids', [])) ? 'checked' : '' }}
                                    >
                                    <span>{{ $employee->nama_lengkap }} ({{ $employee->nik }})</span>
                                </label>
                            @empty
                                <span class="text-gray-400 text-sm">Belum ada data karyawan</span>
                            @endforelse
                        </div>

                        <p class="text-xs text-gray-400 mt-1">
                            <span id="selectedCount">0</span> dari {{ $employees->count() }} karyawan dipilih
                        </p>

                        @error('employee_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        @error('employee_ids.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <select name="payment_status" class="block w-full border rounded px-3 py-2">
                            <option value="Belum Dibayar" {{ old('payment_status') === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="Sudah Dibayar" {{ old('payment_status') === 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                        @error('payment_status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                        <a href="{{ route('admin.payroll-histories.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleAllEmployees(checked) {
            document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const total = document.querySelectorAll('.employee-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = total;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.employee-checkbox').forEach(cb => {
                cb.addEventListener('change', updateSelectedCount);
            });
            updateSelectedCount();
        });
    </script>
</x-app-layout>
