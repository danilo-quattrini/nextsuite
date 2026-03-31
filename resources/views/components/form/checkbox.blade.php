{{--
    CHECKBOX COMPONENT
    ==================
    Two rendering modes controlled by the `wrap` prop:

    MODE 1 — Self-contained (wrap=true, DEFAULT for most use cases)
    Renders a full <label> + <input> + visual mark + optional label text.
    Use this for standalone checkboxes or inside a form directly.

        <x-form.checkbox
            wrap
            id="agree"
            name="agree"
            label="I agree to the terms"
            size="md"
        />

        Or with slot content instead of label prop:
        <x-form.checkbox wrap id="agree" name="agree">
            I agree to the <a href="/terms">terms</a>
        </x-form.checkbox>

    MODE 2 — Input only (wrap=false, default)
    Renders only the bare <input>. Used when an EXTERNAL wrapper (like
    x-toggle-container) provides the <label> and .ds-checkbox-mark.
    IMPORTANT: the .ds-checkbox-mark span MUST immediately follow
    the <input> in the DOM for the CSS :checked + .ds-checkbox-mark
    selector to work. Do not put anything between them.

        <label class="ds-checkbox-container">
            <x-form.checkbox id="remember" name="remember" size="sm" />
            <span class="ds-checkbox-mark"></span>
            <span class="ds-checkbox-label">Remember me</span>
        </label>

    PROPS
    -----
    size           sm | md | lg         (default: md)
    label          string               Label text (wrap mode only)
    wrap           bool                 Render full label wrapper (default: false)
    containerClass string               Extra classes on the <label> wrapper
    boxClass       string               Extra classes on .ds-checkbox-mark
    labelClass     string               Extra classes on .ds-checkbox-label
    inputClass     string               Extra classes on the <input>

    All other attributes (id, name, wire:model, x-model, checked,
    disabled, required) are forwarded directly to the <input>.
--}}
@props([
    'size' => 'md',
    'label' => null,
    'wrap' => false,
    'containerClass' => '',
    'boxClass' => '',
    'labelClass' => '',
    'inputClass' => '',
])

@php
    $allowedSizes = ['sm', 'md', 'lg'];
    $size = strtolower((string) $size);
    $size = in_array($size, $allowedSizes, true) ? $size : 'md';
    $wrap = filter_var($wrap, FILTER_VALIDATE_BOOL);
    $inputSizeClass = "ds-checkbox-input--{$size}";
    $wrapperSizeClass = "ds-checkbox--{$size}";
    $hasSlot = trim((string) $slot) !== '';
    $labelText = $label ?? ($hasSlot ? $slot : null);
    $inputClasses = trim("ds-checkbox-input {$inputSizeClass} {$inputClass}");
    $wrapperClasses = trim(implode(' ', [
        'ds-checkbox',
        $wrapperSizeClass,
        $containerClass,
        $attributes->get('class'),
    ]));
@endphp

@if($wrap)
    <label class="{{ $wrapperClasses }}">
        <input type="checkbox" {{ $attributes->except('class')->merge(['class' => $inputClasses]) }} />
        <span class="ds-checkbox-mark {{ $boxClass }}"></span>
        @if($labelText)
            <span class="ds-checkbox-label {{ $labelClass }}">{{ $labelText }}</span>
        @endif
    </label>
@else
    <input type="checkbox" {{ $attributes->merge(['class' => $inputClasses]) }} />
@endif
