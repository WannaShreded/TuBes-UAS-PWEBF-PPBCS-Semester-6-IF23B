{{-- File: resources/views/admin/users/index.blade.php --}}


<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen User
            </h2>
            @can('delete-users')
                <a href="{{ route('admin.users.trash') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    Recycle Bin
                </a>
            @endcan
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">


                    {{-- Pesan sukses --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <livewire:admin.user-table />
                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama atau email"
                               class="border rounded px-3 py-2 md:col-span-2">
                        <select name="role" class="border rounded px-3 py-2">
                            <option value="">Semua role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" @selected(request('role') === $role)>{{ $role }}</option>
                            @endforeach
                        </select>
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded">Cari</button>
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Reset</a>
                        </div>
                    </form>

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">Nama</th>
                                <th class="p-3">Email</th>
                                <th class="p-3">Role</th>
                                @canany(['edit-users', 'delete-users'])
                                    <th class="p-3">Aksi</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="border-b">
                                    <td class="p-3">{{ $user->name }}</td>
                                    <td class="p-3">{{ $user->email }}</td>
                                    <td class="p-3">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    @canany(['edit-users', 'delete-users'])
                                        <td class="p-3 flex items-center gap-3">
                                            @can('edit-users')
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            @endcan

                                            @can('delete-users')
                                                <button
                                                    onclick="openConfirmModal({
                                                        type: 'danger',
                                                        title: 'Konfirmasi Hapus',
                                                        message: 'Anda akan menghapus user \'{{ addslashes($user->name) }}\'. Tindakan ini tidak dapat dibatalkan.',
                                                        confirmText: 'Ya, Hapus',
                                                        actionUrl: '{{ route('admin.users.destroy', $user) }}',
                                                        actionMethod: 'DELETE'
                                                    })"
                                                    class="text-red-600 hover:underline"
                                                >
                                                    Hapus
                                                </button>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
