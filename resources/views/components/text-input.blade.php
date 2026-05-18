@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-lime-200 focus:border-lime-500 focus:ring-lime-500 rounded-xl shadow-sm']) }}>
