@props([
    'leftIcon' => null,
    'rightIcon' => null,
    'size' => 'md',
    'error' => false,
    'placeholder' => '',
])

@php
    $base = 'input-border';
    $input = 'input';

    $iconColor = $error
        ? 'text-secondary-error group-focus-within:text-secondary-error'
        : 'group-focus-within:text-black';
@endphp

{{-- ========================================================= --}}
{{-- WRAPPER --}}
{{-- ========================================================= --}}
<div
        class="group {{ $base }} {{ $error ? 'has-error' : '' }}"
        x-data="{ show: false, filled: false, error: @json($error) }"
        x-init="filled = $refs.input && $refs.input.value && $refs.input.value.length > 0"
>
    {{-- ========================================================= --}}
    {{-- LEFT ICON (ONLY IF NOT PHONE INPUT) --}}
    {{-- ========================================================= --}}
    @if($leftIcon)
        <x-heroicon
                :name="$leftIcon"
                size="{{ $size }}"
                variant="outline"
                class="{{ $iconColor }}"
                x-bind:class="{ ' text-black': filled && error,
                                'text-primary-grey'  : !filled && !error
                }"
        />
    @endif

    <input
            x-ref="input"
            type="{{ $attributes->get('type') }}"
            class="{{ $input }} {{$attributes->get('class')}}"
            placeholder="{{ $placeholder }}"
            @input="filled = $event.target.value.length > 0"
            {{ $attributes->except('type') }}
    />

    {{-- ========================================================= --}}
    {{-- RIGHT ICON (NOT FOR PHONE INPUTS) --}}
    {{-- ========================================================= --}}
    @if($rightIcon)
        @if($rightIcon !== 'eye')
            <x-heroicon
                    :name="$rightIcon"
                    size="{{ $size }}"
                    variant="outline"
                    class="{{ $iconColor }}"
                    x-bind:class="{ ' text-black': filled && error,
                                'text-primary-grey'  : !filled && !error
                    }"
            />
        @else
            <span
                    @click="show = !show; $refs.input.type = show ? 'text' : 'password'"
                    class="cursor-pointer"
            >
                <span x-show="!show">
                   <x-heroicon
                           name="eye"
                           size="{{ $size }}"
                           variant="outline"
                           class="{{ $iconColor }}"
                           x-bind:class="{ ' text-black': filled && error,
                                'text-primary-grey'  : !filled && !error
                           }"
                   />
                </span>
                <span x-show="show">
                    <x-heroicon
                            name="eye-slash"
                            size="{{ $size }}"
                            variant="outline"
                            class="{{ $iconColor }}"
                            x-bind:class="{ ' text-black': filled && error,
                                'text-primary-grey'  : !filled && !error
                            }"
                    />
                </span>
            </span>
        @endif
    @endif

</div>
