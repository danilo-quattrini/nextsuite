@props([
    'name',
    'value' => null,
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'containerClass' => '',
])

@php
    $inputId = $attributes->get('id');
    if (!$inputId && $name) {
        $suffix = is_null($value) ? 'option' : (string) $value;
        $inputId = $name . '-' . \Illuminate\Support\Str::slug($suffix);
    }
@endphp

<label class="ds-radio-container {{ $containerClass }}">
    <input
        type="radio"
        @if($inputId) id="{{ $inputId }}" @endif
        name="{{ $name }}"
        @if(!is_null($value)) value="{{ $value }}" @endif
        @if($checked) checked @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="ds-radio-input {{ $attributes->get('class') }}"
        {{ $attributes->except(['class', 'id']) }}
    />
    <span class="ds-radio-mark"></span>
    <span>{{ $slot }}</span>
</label>
