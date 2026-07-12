<div>
    <div class="mb-4">
        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Cari..."
               class="w-full md:w-80 border rounded px-3 py-2">
    </div>

    <div wire:loading.class="opacity-60" class="relative transition-opacity duration-200">
        <div wire:loading.flex class="absolute inset-x-0 top-0 z-10 items-center gap-2 rounded-lg bg-white/90 px-3 py-2 text-sm font-medium text-slate-600 shadow-sm" role="status">
            <span class="h-3 w-3 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></span>
            Memperbarui data…
        </div>
        <div class="overflow-x-auto">
            @yield($tableView)
        </div>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
