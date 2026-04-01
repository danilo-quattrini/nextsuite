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
    $inputType = $attributes->get('type', 'text');

    /*
     * Icon color logic:
     * - Error state:  always red
     * - Filled state: black
     * - Default:      primary-grey
     * The Alpine x-bind:class below handles filled/unfilled dynamically.
     * The static $iconColor class covers the error case upfront.
     */
    $iconColor = $error
        ? 'text-secondary-error'
        : 'text-primary-grey group-focus-within:text-black';
@endphp

{{-- ========================================================= --}}
{{-- WRAPPER --}}
{{-- ========================================================= --}}
<div
        class="group input-border {{ $error ? 'has-error' : '' }}"
        x-data="{
                    isVisible: false,
                    hasValue: false,
                    hasError: @json($error)
        }"
        x-init="hasValue = $refs.input?.value?.length > 0"
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
                x-bind:class="{
                    'text-black': hasValue && hasError,
                    'text-primary-grey'  : !hasValue && !hasError
                }"
        />
    @endif

    <input
            x-ref="input"
            type="{{ $inputType }}"
            placeholder="{{ $placeholder }}"
            @input="hasValue = $event.target.value.length > 0"
            {{ $attributes->except(['type', 'class'])->merge(['class' => 'input ' . $attributes->get('class', '')]) }}
    />

    {{-- ========================================================= --}}
    {{-- RIGHT ICON (NOT FOR PHONE INPUTS) --}}
    {{-- ========================================================= --}}
    @if($rightIcon)
        @if($rightIcon === 'eye')
            <span
                    @click="isVisible = !isVisible; $refs.input.type = isVisible ? 'text' : 'password'"
                    class="cursor-pointer shrink-0"
                    role="button"
                    aria-label="Toggle password visibility"
            >
                <span x-show="!isVisible">
                   <x-heroicon
                           name="eye"
                           size="{{ $size }}"
                           variant="outline"
                           class="{{ $iconColor }}"
                           x-bind:class="{
                                'text-black': hasValue && hasError,
                                'text-primary-grey'  : !hasValue && !hasError
                           }"
                   />
                </span>
                <span x-show="isVisible">
                    <x-heroicon
                            name="eye-slash"
                            size="{{ $size }}"
                            variant="outline"
                            class="{{ $iconColor }}"
                            x-bind:class="{
                                'text-black': hasValue && hasError,
                                'text-primary-grey'  : !hasValue && !hasError
                            }"
                    />
                </span>
            </span>
        @else
            <x-heroicon
                    :name="$rightIcon"
                    size="{{ $size }}"
                    variant="outline"
                    class="{{ $iconColor }}"
                    x-bind:class="{
                        'text-black':        hasValue && !hasError,
                        'text-primary-grey': !hasValue && !hasError
                }"
            />
        @endif
    @endif

</div>
