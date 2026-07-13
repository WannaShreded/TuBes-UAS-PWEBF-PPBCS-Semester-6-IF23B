@props(['type' => 'success'])
@php
    $classes = match(strtolower($type)) {
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'error', 'danger' => 'bg-rose-50 border-rose-200 text-rose-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        default => 'bg-blue-50 border-blue-200 text-blue-800',
    };
@endphp
<div {{ $attributes->merge(['class' => "p-4 rounded-[14px] border {$classes} shadow-sm transition-all duration-300 transform animate-fade-in"]) }} role="alert">
    <div class="flex items-center gap-3">
        @if($type === 'success')
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @elseif($type === 'error' || $type === 'danger')
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @else
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @endif
        <span class="text-sm font-medium">{{ $slot }}</span>
    </div>
</div>
