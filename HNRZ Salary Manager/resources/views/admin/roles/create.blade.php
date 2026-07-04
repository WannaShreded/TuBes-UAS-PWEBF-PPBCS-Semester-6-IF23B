<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Role
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf

                    {{-- Nama Role --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role Name
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                               placeholder="contoh: editor">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Checkbox Permission (dikelompokkan) --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Permissions
                        </label>

                        @foreach($permissions as $group => $groupPermissions)
                            <div class="mb-4 p-3 bg-gray-50 rounded">
                                <p class="text-xs font-bold uppercase text-gray-500 mb-2">
                                    {{ strtoupper(str_replace('-', ' ', $group)) }}
                                </p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $permission->name }}"
                                                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                            {{ $permission->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Save Role
                        </button>
                        <a href="{{ route('admin.roles.index') }}"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
