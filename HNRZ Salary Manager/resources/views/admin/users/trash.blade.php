{{-- File: resources/views/admin/users/trash.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Recycle Bin - User
            </h2>
            @can('delete-users')
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    &larr; Kembali ke Manajemen User
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">Nama</th>
                                <th class="p-3">Email</th>
                                <th class="p-3">Role</th>
                                <th class="p-3">Dihapus Pada</th>
                                @can('delete-users')
                                    <th class="p-3">Aksi</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
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
                                    <td class="p-3 text-gray-500">
                                        {{ $user->deleted_at?->format('d M Y H:i') }}
                                    </td>
                                    @can('delete-users')
                                        <td class="p-3 flex items-center gap-3">
                                            <button
                                                type="button"
                                                data-type="warning"
                                                data-title="Konfirmasi Pulihkan"
                                                data-message="Kembalikan user '{{ $user->name }}' ke data utama?"
                                                data-confirm-text="Ya, Pulihkan"
                                                data-action-url="{{ route('admin.users.restore', $user->id) }}"
                                                data-action-method="PATCH"
                                                onclick="openConfirmFromEl(this)"
                                                class="text-green-600 hover:underline"
                                            >
                                                Restore
                                            </button>

                                            <button
                                                type="button"
                                                data-type="danger"
                                                data-title="Konfirmasi Hapus Permanen"
                                                data-message="User '{{ $user->name }}' akan dihapus permanen dan tidak dapat dikembalikan lagi. Lanjutkan?"
                                                data-confirm-text="Ya, Hapus Permanen"
                                                data-action-url="{{ route('admin.users.force-delete', $user->id) }}"
                                                data-action-method="DELETE"
                                                onclick="openConfirmFromEl(this)"
                                                class="text-red-600 hover:underline"
                                            >
                                                Delete Permanently
                                            </button>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-3 text-center text-gray-400">
                                        Recycle Bin user kosong.
                                    </td>
                                </tr>
                            @endforelse
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
