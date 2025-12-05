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


<div x-data="{ show: false }" class="group {{ $base }} {{ $error ? 'has-error' : '' }}">

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
            :type="show ? 'text' : '{{ $attributes->get('type') }}'"
            {{ $attributes->except('type') }}
    />

    {{-- RIGHT ICON --}}
    @if($rightIcon)
        @if($rightIcon != 'eye')
            <x-heroicon :name="$rightIcon"
                        size="md"
                        variant="outline"
                        class="{{$iconColor}}"
            />
        @else
            <span @click="show = !show" class="cursor-pointer">
                <span x-show="!show">
                   <x-heroicon name="eye" size="md" variant="outline" class="{{$iconColor}}" />
                </span>
                <span x-show="show">
                    <x-heroicon name="eye-slash" size="md" variant="outline" class="{{$iconColor}}" />
                </span>
            </span>
        @endif
    @endif
</div>
