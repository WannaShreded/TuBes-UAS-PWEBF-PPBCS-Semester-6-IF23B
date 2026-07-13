@props([
    'title',
    'description',
    'href',
    'count' => null,
    'accent' => 'from-[#7c1fd6] to-[#e91e8c]',
])

<a href="{{ $href }}"
   class="group block rounded-[22px] border border-[#ece7fb] bg-white p-5 shadow-sm shadow-[#5b1fb8]/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#5b1fb8]/10 focus-visible:ring-2 focus-visible:ring-[#7c1fd6] sm:p-6">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-lg font-bold text-[#241a52] font-display">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-[#5b5578]">{{ $description }}</p>
        </div>

        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[12px] bg-gradient-to-br {{ $accent }} text-white shadow-md shadow-[#5b1fb8]/20">
            {{ $icon }}
        </div>
    </div>

    <div class="mt-6 flex items-center justify-between gap-3">
        <span class="text-sm font-medium text-[#5b5578]/80">
            @if($count !== null)
                {{ $count }} data
            @else
                Akses modul
            @endif
        </span>
        <span class="text-sm font-bold text-[#7c1fd6] group-hover:text-[#e91e8c] transition-colors duration-200">
            Buka →
        </span>
    </div>
</a>

