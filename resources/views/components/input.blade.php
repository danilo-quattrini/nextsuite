@props([
    'leftIcon' => null,
    'rightIcon' => null,
    'error' => false,
    'placeholder' => ''
    ])
@php
    $base = ' input-border ';
    $input = ' input ';

    if ($error) {
        $iconColor = ' text-secondary-error group-focus-within:text-secondary-error ';
    }else{
        $iconColor = ' text-primary-grey group-focus-within:text-black ';
    }
@endphp


<div class="group {{ $base }} {{ $error ? 'has-error' : '' }}">

    {{-- LEFT ICON --}}
    @if($leftIcon)
        <x-heroicon :name="$leftIcon"
                    size="md"
                    variant="outline"
                    class="{{$iconColor}}"
        />

    @endif

    {{-- INPUT FIELD --}}
    <input
            placeholder="{{ $placeholder }}"
            class="{{ $input }}"
            {{ $attributes }}
    />

    {{-- RIGHT ICON --}}
    @if($rightIcon)
        <x-heroicon :name="$rightIcon"
                    size="md"
                    variant="outline"
                    class="{{$iconColor}}"
        />
    @endif
</div>
