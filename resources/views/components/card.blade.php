@props([
    'border' => 'default',
    'color' => 'default'
])

@php
    $borders = [
        'default' => 'solid',
        'dashed' => 'dashed'
    ];

    $colors = [
        'default' => 'primary-grey',
        'light-grey' => 'outline-grey',
        'black' => 'black'
    ];
    $class = 'text-center bg-white border border-'. ($borders[$border] ?? $borders['default'])  . ' border-'. ($colors[$color] ?? $colors['default']) .' rounded-md p-6 space-y-2';
@endphp
<div {{ $attributes->merge(['class' => $class])  }}>
    {{ $slot }}
</div>