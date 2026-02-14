@props(['active' => false])

@php
    $class = 'sidebar__link';

    if ($active) {
        $class .= ' sidebar__link--active';
    }
@endphp

<a {{ $attributes->merge(['class' => $class]) }} @if($active) aria-current="page" @endif>
    <span class="sidebar__link-icon">
        {{ $icon }}
        </span>
    <span class="sidebar__link-label">
        {{ $slot }}
    </span>
</a>
