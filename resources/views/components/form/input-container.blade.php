{{--
    INPUT CONTAINER COMPONENT
    =======================
    A vertical flex column that controls the width of a form field group
    (label + input + error message). Wrap any form field combination in this.

    USAGE:
        <x-form.input-container>
            <x-label for="email">Email</x-label>
            <x-form.input name="email" type="email" />
            <x-form.error field="email" />
        </x-form.input-container>

        <x-form.input-container size="medium">
            ...
        </x-form.input-container>

    SIZES
    -----
    full      100% width
    default   ~470px max, shrinks on small screens   (default)
    medium    ~235px max, shrinks on small screens
    small     256px fixed
    compact   160px fixed
    auto      width: auto (shrinks to content)

    NOTE: 'default' and 'medium' widths are defined as CSS custom
    properties in token.css so they stay in sync with your spacing
    scale. If you need a different width, add a token — do not add
    new arbitrary Tailwind values here.
--}}
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