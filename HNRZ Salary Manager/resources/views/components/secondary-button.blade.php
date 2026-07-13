<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border border-[#ece7fb] bg-[#f4f1fb] px-6 py-2.5 font-display text-sm font-semibold tracking-wide text-[#241a52] hover:bg-[#ece7fb] hover:text-[#140b3d] active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#ece7fb] focus:ring-offset-2 disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>

