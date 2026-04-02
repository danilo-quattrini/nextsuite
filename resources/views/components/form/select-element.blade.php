{{--
    SELECT ELEMENT
    ==============
    The native <select> element. Always used inside x-form.select-wrapper.

    USAGE:
        <x-form.select-wrapper>
            <x-form.select-element name="country" wire:model="form.country">
                <x-slot:options>
                    <option value="" disabled selected hidden>Choose...</option>
                    <option value="it">Italy</option>
                </x-slot:options>
            </x-form.select-element>
        </x-form.select-wrapper>

    PROPS
    -----
    name        string   name attribute on <select>
    id          string   id attribute (defaults to name)
    placeholder string   Text for the empty first option (optional).
                         If provided, renders a disabled/selected/hidden
                         option automatically — no need to add it in the slot.
    options     slot     Named slot for <option> elements.

    All other attributes (wire:model, x-model, required, etc.)
    are forwarded directly to <select>.
--}}

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
