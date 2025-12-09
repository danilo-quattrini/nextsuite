@props([
    'input_type' => '',
    'leftIcon' => null,
    'rightIcon' => null,
    'error' => false,
    'placeholder' => '',
])

@php
    $base = 'input-border';
    $input = 'input';
    $isPhone = $input_type === 'phone';

    $iconColor = $error
        ? 'text-secondary-error group-focus-within:text-secondary-error'
        : 'text-primary-grey group-focus-within:text-black';
@endphp

{{-- ========================================================= --}}
{{-- WRAPPER --}}
{{-- ========================================================= --}}
<div
        class="group {{ $base }} {{ $error ? 'has-error' : '' }}"
        x-data="{ show: false }"
>
    {{-- ========================================================= --}}
    {{-- LEFT ICON (ONLY IF NOT PHONE INPUT) --}}
    {{-- ========================================================= --}}
    @if(!$isPhone && $leftIcon)
        <x-heroicon
                :name="$leftIcon"
                size="md"
                variant="outline"
                class="{{ $iconColor }}"
        />
    @endif

    {{-- ========================================================= --}}
    {{-- PHONE INPUT (intl-tel-input) --}}
    {{-- ========================================================= --}}
    @if($isPhone)
        <input
                wire:ignore
                x-ref="input"
                type="tel"
                class="phone-input {{ $input }}"
                placeholder="{{ $placeholder }}"
                {{ $attributes->except('type') }}
        />
    @endif

    {{-- NORMAL INPUT --}}
    @if(!$isPhone)
        <input
                x-ref="input"
                type="{{ $attributes->get('type') }}"
                class="{{ $input }}"
                placeholder="{{ $placeholder }}"
                {{ $attributes->except('type') }}
        />
    @endif

    {{-- ========================================================= --}}
    {{-- RIGHT ICON (NOT FOR PHONE INPUTS) --}}
    {{-- ========================================================= --}}
    @if(!$isPhone && $rightIcon)
        @if($rightIcon !== 'eye')
            <x-heroicon
                    :name="$rightIcon"
                    size="md"
                    variant="outline"
                    class="{{ $iconColor }}"
            />
        @else
            <span
                    @click="show = !show; $refs.input.type = show ? 'text' : 'password'"
                    class="cursor-pointer"
            >
                <span x-show="!show">
                   <x-heroicon name="eye" size="md" variant="outline" class="{{ $iconColor }}" />
                </span>
                <span x-show="show">
                    <x-heroicon name="eye-slash" size="md" variant="outline" class="{{ $iconColor }}" />
                </span>
            </span>
        @endif
    @endif

</div>
