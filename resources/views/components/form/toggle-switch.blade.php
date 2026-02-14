@props([
    'size' => 'md',
    'label' => null,
    'description' => null,
    'checked' => false,
    'disabled' => false,
])

@php
    $allowedSizes = ['sm', 'md', 'lg'];
    $size = strtolower((string) $size);
    $size = in_array($size, $allowedSizes, true) ? $size : 'md';

    $hasSlot = trim((string) $slot) !== '';
    $labelText = $label ?? ($hasSlot ? $slot : null);

    $wrapperClasses = trim(implode(' ', [
        'toggle-switch',
        "toggle-switch--{$size}",
        $attributes->get('class', ''),
    ]));
@endphp

<label class="{{ $wrapperClasses }}">
    <input
            type="checkbox"
            role="switch"
            class="toggle-switch__input"
            {{ $attributes->except('class') }}
            @checked($checked)
            @disabled($disabled)
    />

    <div class="toggle-switch__track">
        <div class="toggle-switch__thumb"></div>
    </div>

    @if($labelText || $description)
        <span class="toggle-switch__text">
            @if($labelText)
                <span class="toggle-switch__label">{{ $labelText }}</span>
            @endif
            @if($description)
                <span class="toggle-switch__description">{{ $description }}</span>
            @endif
        </span>
    @endif
</label>