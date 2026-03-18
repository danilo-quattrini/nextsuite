@props([
    'value' => null,
    'size' => 'md',
    'empty' => '—',
    'variant' => null,
])

@php
    $numeric = is_null($value) ? null : (float) $value;
    $display = is_null($value) ? $empty : number_format((float) $value);

    $variant = $variant ?? match (true) {
        $numeric < 50 => 'error',
        $numeric >= 50 && $numeric < 70 => 'warning',
        $numeric >= 70 => 'success',
        default => 'white',
    };
@endphp

<x-tag :variant="$variant" :size="$size">
    {{ $display }}
</x-tag>
