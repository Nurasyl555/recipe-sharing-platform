@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-lime-600 bg-lime-100 p-3 rounded-lg border border-lime-200']) }}>
        {{ $status }}
    </div>
@endif
