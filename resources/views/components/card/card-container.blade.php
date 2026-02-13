@props([
    'title' => 'Title',
    'size' => null
])
@php
    $sizes = [
        'sm' => ' text-sm ',
        'md' => ' text-md ',
        'lg' => ' text-base ',
        'xl' => ' text-xl ',
        '2xl' => ' text-2xl ',
        '3xl' => ' text-3xl '
    ]
@endphp
<div class="card-container">
    <div class="card-head">
        @if('size')
            <span {{ $attributes->merge(['class' => $sizes[$size] ?? $sizes['3xl'] ] ) }}> {{ $title }} </span>
        @else
            <h3>{{ $title }}</h3>
        @endif
        {{ $action ?? null }}
    </div>
    {{ $slot }}
</div>
