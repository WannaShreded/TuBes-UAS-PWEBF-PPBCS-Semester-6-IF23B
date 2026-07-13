@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-[14px] border-[#e6e0f7] bg-[#f4f1fb] text-[#241a52] placeholder-[#5b5578]/55 focus:border-[#7c1fd6] focus:bg-white focus:ring-[3px] focus:ring-[#7c1fd6]/10 focus:outline-none transition-all duration-200']) }}>

