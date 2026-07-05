<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Metode Penggajian Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <p class="text-sm text-gray-600 mb-4">
                    Pilih salah satu metode penggajian yang Anda inginkan.
                </p>

                <form method="POST" action="{{ route('employee.payroll-methods.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-3">
                        @foreach($payrollMethods as $payrollMethod)
                            <label class="flex items-start gap-3 rounded border p-4 hover:bg-gray-50">
                                <input type="radio" name="payroll_method_id" value="{{ $payrollMethod->id }}" class="mt-1"
                                    {{ old('payroll_method_id', $employee->payroll_method_id) == $payrollMethod->id ? 'checked' : '' }}>
                                <span>
                                    <span class="font-semibold text-gray-900">{{ $payrollMethod->name }}</span>
                                    <span class="block text-sm text-gray-500">{{ $payrollMethod->type }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @error('payroll_method_id')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Pilihan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
