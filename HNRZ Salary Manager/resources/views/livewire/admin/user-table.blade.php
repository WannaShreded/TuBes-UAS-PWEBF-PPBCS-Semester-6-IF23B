<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari nama, email, atau role..."
               class="w-full md:col-span-2 border rounded px-3 py-2">

        <select wire:model.live="role" class="border rounded px-3 py-2">
            <option value="">Semua role</option>
            @foreach($roles as $roleOption)
                <option value="{{ $roleOption }}">{{ $roleOption }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading.class="opacity-60" class="relative transition-opacity duration-200">
        <div wire:loading.flex class="absolute inset-x-0 top-0 z-10 items-center gap-2 rounded-lg bg-white/90 px-3 py-2 text-sm font-medium text-slate-600 shadow-sm" role="status"><span class="h-3 w-3 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></span>Memperbarui data…</div>
        <div class="overflow-x-auto">
            <table class="app-table">
                <thead>
                <tr>
                    <th><button wire:click="sortBy('name')">Nama</button></th>
                    <th><button wire:click="sortBy('email')">Email</button></th>
                    <th class="p-3">Role</th>
                    @canany(['edit-users', 'delete-users'])
                        <th class="p-3">Aksi</th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @foreach($items as $user)
                    <tr class="border-b" wire:key="user-row-{{ $user->id }}">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        @canany(['edit-users', 'delete-users'])
                            <td class="p-3 flex items-center gap-3">
                                @can('edit-users')
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                                @endcan
                                @can('delete-users')
                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
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
        </div>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
