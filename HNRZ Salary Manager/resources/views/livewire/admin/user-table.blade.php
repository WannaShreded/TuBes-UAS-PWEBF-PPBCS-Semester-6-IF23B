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

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
        <div class="overflow-x-auto">
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
                @foreach($items as $user)
                    <tr class="border-b">
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
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
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
