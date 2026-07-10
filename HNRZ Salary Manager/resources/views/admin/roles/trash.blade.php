<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Recycle Bin - Role
        </h2>
        @can('delete-roles')
            <a href="{{ route('admin.roles.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                &larr; Kembali ke Role Management
            </a>
        @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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
                                <th class="p-3">No</th>
                                <th class="p-3">Role Name</th>
                                <th class="p-3">Permissions</th>
                                <th class="p-3">Dihapus Pada</th>
                                @can('delete-roles')
                                    <th class="p-3">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $index => $role)
                                <tr class="border-b">
                                    <td class="p-3">{{ $roles->firstItem() + $index }}</td>
                                    <td class="p-3 font-semibold">{{ $role->name }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                            {{ $role->permissions_count }} permission
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-500">
                                        {{ $role->deleted_at?->format('d M Y H:i') }}
                                    </td>
                                    @can('delete-roles')
                                        <td class="p-3">
                                            <div class="flex items-center gap-3">
                                                <button
                                                    type="button"
                                                    data-type="warning"
                                                    data-title="Konfirmasi Pulihkan"
                                                    data-message="Kembalikan role '{{ $role->name }}' ke data utama?"
                                                    data-confirm-text="Ya, Pulihkan"
                                                    data-action-url="{{ route('admin.roles.restore', $role->id) }}"
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
                                                    data-message="Role '{{ $role->name }}' akan dihapus permanen dan tidak dapat dikembalikan lagi. Lanjutkan?"
                                                    data-confirm-text="Ya, Hapus Permanen"
                                                    data-action-url="{{ route('admin.roles.force-delete', $role->id) }}"
                                                    data-action-method="DELETE"
                                                    onclick="openConfirmFromEl(this)"
                                                    class="text-red-600 hover:underline"
                                                >
                                                    Delete Permanently
                                                </button>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-3 text-center text-gray-400">
                                        Recycle Bin role kosong.
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
