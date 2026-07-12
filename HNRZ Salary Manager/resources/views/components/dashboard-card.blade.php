@props([
    'title',
    'description',
    'href',
    'count' => null,
    'accent' => 'from-indigo-500 to-blue-600',
])

<a href="{{ $href }}"
   class="group block rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-shadow duration-200 ease-in-out hover:shadow-md focus-visible:ring-2 focus-visible:ring-indigo-600 sm:p-6">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-gray-600">{{ $description }}</p>
        </div>

        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br {{ $accent }} text-white shadow-sm">
            {{ $icon }}
        </div>
    </div>

    <div class="mt-6 flex items-center justify-between gap-3">
        <span class="text-sm font-medium text-gray-500">
            @if($count !== null)
                {{ $count }} data
            @else
                Akses modul
            @endif
        </span>
        <span class="text-sm font-semibold text-indigo-600">
            Buka →
        </span>
    </div>
</a>
