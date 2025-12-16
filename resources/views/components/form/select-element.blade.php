@props([
    'name' => null,
    'id' => null,
    'options' => []
])
<select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $attributes->merge(['class' => 'select absolute inset-0 w-full h-full cursor-pointer opacity-0']) }}
        wire:model.defer="{{$model}}"
        x-ref="select"
        x-on:change="
        let selected = $refs.select.selectedOptions[0];
        $dispatch('select-changed', { label: selected ? selected.text : 'Select an option' });
    "
        x-on:input="
        let selected = $refs.select.selectedOptions[0];
        $dispatch('select-changed', { label: selected ? selected.text : 'Select an option' });
    "
>
    {{-- explicit placeholder so first real option triggers change when selected --}}
    <option value="0" disabled hidden>Select an option</option>

    @foreach ($options as $value => $label)
        <option value="{{ $value + 1}}">
            {{ $label->name }}
        </option>
    @endforeach
</select>

<span
        class="pointer-events-none font-medium text-black"
        x-data="{ label: 'Select an option' }"
        x-init="
        let selected = $refs.select?.selectedOptions?.[0];
        label = selected && selected.text ? selected.text : 'Select an option';
    "
        x-on:select-changed.window="
        label = $event.detail.label;
    "
        x-text="label"
></span>
