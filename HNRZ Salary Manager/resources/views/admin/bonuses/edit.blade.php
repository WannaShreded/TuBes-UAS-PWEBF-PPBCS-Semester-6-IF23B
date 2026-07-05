<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Bonus: {{ $bonus->nama_bonus }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.bonuses.update', $bonus) }}">
                    @csrf
                    @method('PUT')

                    {{-- Nama Bonus --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Bonus
                        </label>
                        <input type="text" name="nama_bonus"
                               value="{{ old('nama_bonus', $bonus->nama_bonus) }}"
                               class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('nama_bonus')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nominal Bonus --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nominal Bonus (Rp)
                        </label>
                        <input type="number" name="nominal_bonus"
                               value="{{ old('nominal_bonus', $bonus->nominal_bonus) }}"
                               min="0" step="1000"
                               class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('nominal_bonus')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Bonus --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Bonus
                        </label>
                        <select name="jenis_bonus"
                                class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Tetap"
                                {{ old('jenis_bonus', $bonus->jenis_bonus) === 'Tetap' ? 'selected' : '' }}>
                                Tetap
                            </option>
                            <option value="Variabel"
                                {{ old('jenis_bonus', $bonus->jenis_bonus) === 'Variabel' ? 'selected' : '' }}>
                                Variabel
                            </option>
                        </select>
                        @error('jenis_bonus')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Periode Bonus --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Periode Bonus
                        </label>
                        {{-- Format Y-m diperlukan untuk input type="month" --}}
                        <input type="month" name="periode_bonus"
                               value="{{ old('periode_bonus', $bonus->periode_bonus->format('Y-m')) }}"
                               class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('periode_bonus')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="keterangan" rows="3"
                                  class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('keterangan', $bonus->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.bonuses.index') }}"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
