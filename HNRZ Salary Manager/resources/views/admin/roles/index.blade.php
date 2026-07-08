<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Role Management
        </h2>
        @can('create-roles')
            <a href="{{ route('admin.roles.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                + Add Role
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Pesan sukses / error --}}
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
                                <th class="p-3">No</th>
                                <th class="p-3">Role Name</th>
                                <th class="p-3">Permissions</th>
                                @canany(['edit-roles', 'delete-roles'])
                                    <th class="p-3">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $index => $role)
                                <tr class="border-b">
                                <td class="p-3">{{ $roles->firstItem() + $index }}</td>                                    <td class="p-3 font-semibold">{{ $role->name }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                            {{ $role->permissions_count }} permission
                                        </span>
                                    </td>
                                    @canany(['edit-roles', 'delete-roles'])
                                        <td class="p-3">
                                            <div class="flex items-center gap-3">
                                                @can('edit-roles')
                                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                                    class="text-blue-600 hover:underline">Edit</a>
                                                @endcan

                                                @can('delete-roles')
                                                    <button
                                                        type="button"
                                                        data-type="danger"
                                                        data-title="Konfirmasi Hapus"
                                                        data-message="Anda akan menghapus role '{{ $role->name }}'. Tindakan ini tidak dapat dibatalkan."
                                                        data-confirm-text="Ya, Hapus"
                                                        data-action-url="{{ route('admin.roles.destroy', $role) }}"
                                                        data-action-method="DELETE"
                                                        onclick="openConfirmFromEl(this)"
                                                        class="text-red-600 hover:underline"
                                                    >
                                                        Hapus
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-3 text-center text-gray-400">
                                        Belum ada role.
                                    </td>
                                </tr>
                            @endforelse
                       </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $roles->links() }}
                        </div>

                        </div>
            </div>
        </div>
    </div>
</x-app-layout>
