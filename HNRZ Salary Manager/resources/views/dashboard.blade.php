{{-- File: resources/views/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <p class="text-lg">
                        Selamat datang,
                        <strong>{{ Auth::user()->name }}</strong>!
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Role Anda:
                        <span class="font-semibold text-blue-600">
                            {{ Auth::user()->getRoleNames()->implode(', ') }}
                        </span>
                    </p>

                    @canany(['view-users', 'view-roles'])
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-blue-800 mb-3">Menu Admin</h3>
                            <div class="flex gap-3">

                                @can('view-users')
                                    <a href="{{ route('admin.users.index') }}"
                                    class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Kelola User
                                    </a>
                                @endcan

                                @can('view-roles')
                                    <a href="{{ route('admin.roles.index') }}"
                                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        Kelola Role
                                    </a>
                                @endcan

                            </div>
                        </div>
                    @endcanany

                    {{-- Pesan untuk Manager --}}
                    @role('manager')
                        <div class="mt-6 p-4 bg-green-50 rounded-lg">
                            <h3 class="font-semibold text-green-800 mb-2">
                                Informasi Manager
                            </h3>
                            <p class="text-green-700 text-sm">
                                Anda login sebagai Manager. Anda hanya dapat mengakses halaman dashboard.
                            </p>
                        </div>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
