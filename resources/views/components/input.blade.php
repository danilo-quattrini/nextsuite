@props([
    'active' => false,
    'fill' => false,
    'error' => false,
    'disabled' => false,
    'leftIcon' => null,
    'rightIcon' => null,
    'placeholder' => ''
    ])
@php
    $base = ' input-border ';
    $style = [
        'default'      => 'outline-outline-grey outline-1',
        'active'    => 'outline-primary outline-2',
        'fill'    => 'outline-black outline-2',
        'error'     => 'outline-secondary-error outline-2',
        'disabled'  => 'bg-light text-primary-grey outline-outline-grey opacity-60 cursor-not-allowed outline-1 ',
    ];

    if ($disabled) {
        $base .= ' ' . $style['disabled'];
    } elseif ($error) {
        $base .= ' ' . $style['error'];
    } elseif ($active) {
        $base .= ' ' . $style['active'];
    } elseif($fill) {
        $base .= ' ' . $style['fill'];
    } else {
        $base .= ' ' . $style['default'];
    }


    $input = ' input ';
@endphp


<div class="{{ $base }}">

    {{-- LEFT ICON --}}
    @if($leftIcon)
        <x-heroicon :name="$rightIcon"
                    size="md"
                    variant="solid"
                    class="{{ $error ? 'text-secondary-error' : 'text-primary-grey' }}"
        />

    @endif

    {{-- INPUT FIELD --}}
    <input
            {{ $disabled ? 'disabled' : '' }}
            placeholder="{{ $placeholder }}"
            class="{{ $input }}"
            {{ $attributes }}
    />

    {{-- RIGHT ICON --}}
    @if($rightIcon)
        <x-heroicon :name="$rightIcon"
                    size="md"
                    variant="solid"
                    class="{{ $error ? 'text-secondary-error' : 'text-primary-grey' }}" />
    @endif
</div>

{{-- ERROR TEXT --}}
@if($error && $slot)
    <x-input-error/>
@endif
