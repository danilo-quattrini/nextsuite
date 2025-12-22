@props(['active' => false])

@php
    $class = ' flex justify-start items-center gap-2 px-4 py-2 rounded-md';
    $text  = ' font-medium text-base flex-1 ';
    if($active)
    {
        $class .= ' text-white bg-primary ';
    }
@endphp

<a {{$attributes->merge(['class' => $class])}} >
    {{ $icon }}
    <span class="{{ $text }}"> {{ $slot }} </span>
</a>