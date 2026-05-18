@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-lime-500 text-sm font-bold leading-5 text-lime-900 focus:outline-none focus:border-lime-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-lime-700 hover:text-lime-900 hover:border-lime-300 focus:outline-none focus:text-lime-900 focus:border-lime-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
