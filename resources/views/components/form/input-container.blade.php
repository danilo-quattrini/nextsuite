@props([
    'size' => 'default'
])
@php
    $sizes = [
        'full' => ' w-full ',
        'default' => ' w-117.5 max-w-full',
        'medium' => ' w-58.75 max-w-full ',
        'small' => ' w-64 ',
        'compact' => ' w-40 ',
        'auto' => ' w-auto ',
    ];

    $class = '  flex flex-col justify-start items-start gap-4 ' . ' ' . ($sizes[$size] ?? $sizes['default'] )

@endphp
<div {{ $attributes->merge(['class' => $class]) }}>
    {{$slot}}
</div>