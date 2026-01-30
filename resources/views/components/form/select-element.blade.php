@props([
    'name' => null,
    'id' => null,
    'options' => null
])
<select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $attributes->merge(['class' => 'select']) }}
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
    {{ $options ?? $slot }}
</select>
