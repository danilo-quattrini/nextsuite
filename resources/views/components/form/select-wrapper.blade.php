@props([
    'error' => false,
    'base' => 'select-border'
])
{{-- ========================================================= --}}
{{-- WRAPPER --}}
{{-- ========================================================= --}}
<div
     x-data="searchableSelect()"
     x-init="init()"
     x-on:focusin="handleFocusIn()"
     x-on:blur="handleBlur()"
     x-on:change="handleChange()"
     x-on:click="focusSelect()"
     x-on:keydown="handleTypeSearch($event)"
     :class="{ 'open': open, 'has-value': hasValue }"
     class="{{ $base }} {{ $error ? 'has-error' : '' }}"
     tabindex="0"
>


    {{ $slot }}

    <x-heroicon
            name="chevron-right"
            size="md"
            variant="outline"
            class="select-arrow"
    />
</div>
