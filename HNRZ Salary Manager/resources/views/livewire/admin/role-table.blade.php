<div>
    <div class="mb-4">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari role..."
               class="w-full md:w-80 border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
        <div class="overflow-x-auto">
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
                @forelse($items as $index => $role)
                    <tr class="border-b">
                        <td class="p-3">{{ $items->firstItem() + $index }}</td>
                        <td class="p-3 font-semibold">{{ $role->name }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">{{ $role->permissions_count }} permission</span>
                        </td>
                        @canany(['edit-roles', 'delete-roles'])
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @can('edit-roles')
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-blue-600 hover:underline">Edit</a>
                                    @endcan
                                    @can('delete-roles')
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-3 text-center text-gray-400">Belum ada role.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
