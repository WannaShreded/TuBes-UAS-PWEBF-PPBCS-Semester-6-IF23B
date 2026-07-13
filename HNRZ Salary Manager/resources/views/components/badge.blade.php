@props(['type' => 'info'])
@php
    $classes = match(strtolower($type)) {
        'success', 'lunas', 'aktif', 'sudah dibayar' => 'from-emerald-500/10 to-teal-500/10 text-emerald-700 border-emerald-500/20',
        'danger', 'ditolak', 'nonaktif' => 'from-rose-500/10 to-pink-500/10 text-rose-700 border-rose-500/20',
        'warning', 'pending', 'menunggu', 'belum dibayar' => 'from-amber-500/10 to-orange-500/10 text-amber-700 border-amber-500/20',
        default => 'from-[#7c1fd6]/10 to-[#e91e8c]/10 text-[#7c1fd6] border-[#7c1fd6]/20',
    };
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider bg-gradient-to-r border {$classes}"]) }}>
    {{ $slot }}
</span>
