<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Metode Penggajian
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.payroll-methods.update', $payrollMethod) }}" id="editForm">
                    @csrf
                    @method('PUT')

                    {{-- Pilih Metode (diisi manual) --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Metode</label>
                        <input type="text" name="type" value="{{ old('type', $payrollMethod->type) }}"
                               class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama Metode --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Metode</label>
                        <input type="text" name="name" value="{{ old('name', $payrollMethod->name) }}"
                               class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $payrollMethod->is_active) ? 'checked' : '' }}>
                            Aktifkan metode ini
                        </label>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="description" rows="3" placeholder="Isi Deskripsi Metode Penggajian (Opsional)"
                                  class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('description', $payrollMethod->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="button"
                            data-type="warning"
                            data-title="Konfirmasi Update"
                            data-message="Anda akan menyimpan perubahan metode gaji '{{ $payrollMethod->name }}'. Lanjutkan?"
                            data-confirm-text="Ya, Simpan"
                            data-form-id="editForm"
                            onclick="openConfirmFromEl(this)"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.payroll-methods.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
