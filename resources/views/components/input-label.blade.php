@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-lime-800']) }}>
    {{ $value ?? $slot }}
</label>
