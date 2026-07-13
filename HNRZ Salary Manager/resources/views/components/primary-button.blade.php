<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#7c1fd6] to-[#e91e8c] px-6 py-2.5 font-display text-sm font-bold uppercase tracking-[0.14em] text-white shadow-lg shadow-[#e91e8c]/30 hover:opacity-90 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#e91e8c] focus:ring-offset-2 transition-all duration-200']) }}>
    {{ $slot }}
</button>

