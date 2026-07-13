<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-[18px] border border-[#ece7fb] bg-white shadow-sm shadow-[#5b1fb8]/5']) }}>
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>
