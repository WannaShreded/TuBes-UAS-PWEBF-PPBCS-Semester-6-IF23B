<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Riwayat Pembayaran Gaji
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.payroll-histories.update', $payrollHistory) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                        <select name="employee_id" class="block w-full border rounded px-3 py-2">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $payrollHistory->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->nama_lengkap }} ({{ $employee->nik }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <select name="payment_status" class="block w-full border rounded px-3 py-2">
                            <option value="Belum Dibayar" {{ old('payment_status', $payrollHistory->payment_status) === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="Sudah Dibayar" {{ old('payment_status', $payrollHistory->payment_status) === 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                        @error('payment_status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
                        <a href="{{ route('admin.payroll-histories.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
