@props([
    'name' => null,
    'id' => null,
    'error' => false,
    'placeholder' => 'Select an option',
    'options' => [],
])

<select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $attributes->merge(['class' => 'select']) }}
        wire:model.defer="field"
>
    @if($placeholder)
        <option value="" disabled selected>{{ $placeholder }}</option>
    @endif

    @foreach ($options as $value => $label)
        <option value="{{ $value }}">
            {{ $label->name }}
        </option>
    @endforeach
</select>
