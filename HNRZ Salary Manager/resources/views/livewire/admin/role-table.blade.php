<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari role..."
               class="w-full md:col-span-2 border rounded px-3 py-2">

        <select wire:model.live="permission" class="border rounded px-3 py-2">
            <option value="">Semua permission</option>
            @foreach($permissions as $permissionOption)
                <option value="{{ $permissionOption }}">{{ $permissionOption }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading.class="opacity-60" class="relative transition-opacity duration-200">
        <div wire:loading.flex class="absolute inset-x-0 top-0 z-10 items-center gap-2 rounded-lg bg-white/90 px-3 py-2 text-sm font-medium text-slate-600 shadow-sm" role="status"><span class="h-3 w-3 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></span>Memperbarui data…</div>
        <div class="overflow-x-auto">
            <table class="app-table">
                <thead>
                <tr>
                    <th class="p-3">No</th>
                    <th><button wire:click="sortBy('name')">Role Name</button></th>
                    <th class="p-3">Permissions</th>
                    @canany(['edit-roles', 'delete-roles'])
                        <th class="p-3">Action</th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $role)
                    <tr class="border-b" wire:key="role-row-{{ $role->id }}">
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
                                        <button
                                            type="button"
                                            wire:click="confirmDelete({{ $role->id }}, '{{ addslashes($role->name) }}')"
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
