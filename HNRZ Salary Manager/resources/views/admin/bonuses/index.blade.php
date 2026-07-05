<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Bonus
            </h2>
            @can('create-bonuses')
                <a href="{{ route('admin.bonuses.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    + Tambah Bonus
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

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Bonus</th>
                                <th class="p-3">Nominal</th>
                                <th class="p-3">Jenis</th>
                                <th class="p-3">Periode</th>
                                <th class="p-3">Keterangan</th>
                                @canany(['edit-bonuses', 'delete-bonuses'])
                                    <th class="p-3">Aksi</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bonuses as $index => $bonus)
                                <tr class="border-b">
                                    <td class="p-3">{{ $bonuses->firstItem() + $index }}</td>
                                    <td class="p-3 font-medium">{{ $bonus->nama_bonus }}</td>
                                    <td class="p-3">{{ $bonus->nominal_format }}</td>
                                    <td class="p-3">
                                        <span @class([
                                            'px-2 py-1 rounded text-xs font-medium',
                                            'bg-blue-100 text-blue-800'  => $bonus->jenis_bonus === 'Tetap',
                                            'bg-orange-100 text-orange-800' => $bonus->jenis_bonus === 'Variabel',
                                        ])>
                                            {{ $bonus->jenis_bonus }}
                                        </span>
                                    </td>
                                    <td class="p-3">{{ $bonus->periode_label }}</td>
                                    <td class="p-3 text-gray-500">
                                        {{ $bonus->keterangan ?? '-' }}
                                    </td>
                                    @canany(['edit-bonuses', 'delete-bonuses'])
                                        <td class="p-3">
                                            <div class="flex items-center gap-3">
                                                @can('edit-bonuses')
                                                    <a href="{{ route('admin.bonuses.edit', $bonus) }}"
                                                       class="text-blue-600 hover:underline">Edit</a>
                                                @endcan
                                                @if($bonus->jenis_bonus === 'Tetap')
                                                    @can('edit-bonuses')
                                                        <form action="{{ route('admin.bonuses.give-to-all', $bonus) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Berikan bonus &quot;{{ $bonus->nama_bonus }}&quot; ke SEMUA karyawan?')">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="text-purple-600 hover:underline">
                                                                Berikan ke Semua
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endif
                                                @can('delete-bonuses')
                                                    <form action="{{ route('admin.bonuses.destroy', $bonus) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus bonus ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="text-red-600 hover:underline">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-3 text-center text-gray-400">
                                        Belum ada data bonus.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $bonuses->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
