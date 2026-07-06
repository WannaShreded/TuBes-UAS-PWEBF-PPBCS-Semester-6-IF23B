<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(isset($employee) && $employee)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl space-y-6">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Employee Profile') }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Your full employee profile information is shown below.') }}</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Employee ID') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->id_pekerja ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('NIK') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->nik ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Full Name') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->nama_lengkap ?? $user->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Email') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->email ?? $user->email ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Phone Number') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->no_telepon ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Address') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->alamat ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Position') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->position?->name ?? $employee->jabatan ?? __('No Position Assigned') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Role') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->role ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Salary') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $employee->position?->salary ? 'Rp ' . number_format($employee->position->salary, 0, ',', '.') : __('No Position Assigned') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Payroll Method') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->payrollMethod?->name ?? __('Not Selected') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Bank Name') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->nama_bank ?: __('No Bank Information') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Bank Account Number') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->nomor_rekening ?: __('No Bank Information') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
