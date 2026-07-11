<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Metode Gaji
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
                    Pilih salah satu metode gaji yang tersedia. Setelah memilih, sistem akan menampilkan form yang sesuai untuk Anda isi.
                </p>

                <form method="POST" action="{{ route('employee.payroll-methods.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-3 mb-6">
                        @foreach($payrollMethods as $payrollMethod)
                            <label class="flex items-start gap-3 rounded border p-4 hover:bg-gray-50">
                                <input type="radio"
                                       name="payroll_method_id"
                                       value="{{ $payrollMethod->id }}"
                                       data-method-type="{{ strtolower($payrollMethod->type) }}"
                                       class="mt-1 payroll-method-option"
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

                    <div id="payment-details" class="space-y-4" style="display:none;">
                        <div id="bank-help" class="text-sm text-gray-600" style="display:none;">
                            Data rekening bank hanya dapat diisi melalui akun Anda sendiri. Silakan isi nomor rekening yang sesuai.
                        </div>
                        <div id="ewallet-help" class="text-sm text-gray-600" style="display:none;">
                            Silakan isi nomor e-wallet yang akan digunakan untuk menerima gaji.
                        </div>
                        <div id="cash-help" class="text-sm text-gray-600" style="display:none;">
                            Metode Cash tidak memerlukan data rekening atau e-wallet.
                        </div>

                        <div id="bank-account-field" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $employee->nomor_rekening) }}" class="block w-full border rounded px-3 py-2">
                            @error('nomor_rekening')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div id="ewallet-account-field" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor E-Wallet</label>
                            <input type="text" name="nomor_e_wallet" value="{{ old('nomor_e_wallet', $employee->nomor_e_wallet) }}" class="block w-full border rounded px-3 py-2">
                            @error('nomor_e_wallet')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const methodOptions = document.querySelectorAll('.payroll-method-option');
                        const paymentDetails = document.getElementById('payment-details');
                        const bankHelp = document.getElementById('bank-help');
                        const ewalletHelp = document.getElementById('ewallet-help');
                        const cashHelp = document.getElementById('cash-help');
                        const bankAccountField = document.getElementById('bank-account-field');
                        const ewalletAccountField = document.getElementById('ewallet-account-field');
                        const accountInput = document.querySelector('[name="nomor_rekening"]');
                        const ewalletInput = document.querySelector('[name="nomor_e_wallet"]');

                        function updatePaymentDetailsVisibility() {
                            const selectedOption = document.querySelector('.payroll-method-option:checked');
                            const methodType = selectedOption?.dataset.methodType || '';
                            const isBank = methodType.includes('bank');
                            const isEwallet = methodType.includes('wallet') || methodType.includes('e-wallet') || methodType.includes('ewallet');
                            const isCash = !isBank && !isEwallet;

                            paymentDetails.style.display = isBank || isEwallet || isCash ? 'block' : 'none';
                            bankHelp.style.display = isBank ? 'block' : 'none';
                            ewalletHelp.style.display = isEwallet ? 'block' : 'none';
                            cashHelp.style.display = isCash ? 'block' : 'none';
                            bankAccountField.style.display = isBank ? 'block' : 'none';
                            ewalletAccountField.style.display = isEwallet ? 'block' : 'none';

                            if (accountInput) {
                                accountInput.required = isBank;
                            }
                            if (ewalletInput) {
                                ewalletInput.required = isEwallet;
                            }

                            if (!isBank && accountInput) {
                                accountInput.value = '';
                            }
                            if (!isEwallet && ewalletInput) {
                                ewalletInput.value = '';
                            }
                        }

                        methodOptions.forEach(option => {
                            option.addEventListener('change', updatePaymentDetailsVisibility);
                        });

                        updatePaymentDetailsVisibility();
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
