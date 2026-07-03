{{-- File: resources/views/admin/users/edit.blade.php --}}


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">


                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')


                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="mt-1 block w-full border rounded px-3 py-2">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="mt-1 block w-full border rounded px-3 py-2">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Role --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" class="mt-1 block w-full border rounded px-3 py-2">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                            Batal
                        </a>
                    </div>


                </form>
            </div>
        </div>
    </div>
</x-app-layout>
