@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-lime-500 text-start text-base font-bold text-lime-700 bg-lime-50 focus:outline-none focus:text-lime-800 focus:bg-lime-100 focus:border-lime-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-lime-600 hover:text-lime-800 hover:bg-lime-50 hover:border-lime-300 focus:outline-none focus:text-lime-800 focus:bg-lime-50 focus:border-lime-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
