@props([
    'title',
    'description',
    'href',
    'count' => null,
    'accent' => 'from-indigo-500 to-blue-600',
])

<a href="{{ $href }}"
   class="group block rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-gray-600">{{ $description }}</p>
        </div>

        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $accent }} text-white shadow-sm">
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
        <span class="text-sm font-semibold text-indigo-600 transition group-hover:translate-x-1">
            Buka →
        </span>
    </div>
</a>
