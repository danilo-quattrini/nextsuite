@props([
    'value' => null,
    'size' => 'default',
    'empty' => '—',
    'variant' => null,
])

@php
    $numeric = is_null($value) ? null : (float) $value;
    $display = is_null($value) ? $empty : number_format((float) $value);

    $variant = $variant ?? match (true) {
        $numeric < 50 => 'red',
        $numeric >= 50 && $numeric < 70 => 'yellow',
        $numeric >= 70 => 'green',
        default => 'white',
    };
@endphp

<x-tag :variant="$variant" :size="$size">
    {{ $display }}
</x-tag>
