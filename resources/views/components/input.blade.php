{{--
    INPUT COMPONENT
    ===============
    A styled text input with optional left/right icons and password toggle.
    Wraps a native <input> inside a .input-border container.

    USAGE — basic:
        <x-form.input
            name="email"
            type="email"
            placeholder="you@example.com"
            wire:model="email"
        />

    USAGE — with icons:
        <x-form.input
            name="email"
            type="email"
            leftIcon="envelope"
            rightIcon="check"
            placeholder="Email"
            :error="$errors->has('email')"
        />

    USAGE — password with toggle:
        <x-form.input
            name="password"
            type="password"
            leftIcon="lock-closed"
            rightIcon="eye"
            placeholder="Password"
        />

    PROPS
    -----
    leftIcon     string   Heroicon name for the left slot (optional)
    rightIcon    string   Heroicon name for the right slot. Pass "eye"
                          to get the password show/hide toggle.
    size         string   Icon size passed to x-heroicon (default: lg)
    error        bool     Applies has-error styling (default: false)
    placeholder  string   Native placeholder text (default: '')

    All other attributes (name, type, id, wire:model, x-model,
    required, disabled, autocomplete, etc.) are forwarded to <input>.

    NOTES
    -----
    - Default input type is "text". Always pass type="password" explicitly
      when using rightIcon="eye" or the toggle will not work correctly.
    - The Alpine `hasValue` variable tracks whether the input is filled
      to drive icon color changes.
    - The Alpine `isVisible` variable tracks password visibility state.
--}}
@props([
    'leftIcon' => null,
    'rightIcon' => null,
    'size' => 'lg',
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
