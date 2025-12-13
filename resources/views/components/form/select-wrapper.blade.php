@props([
    'error' => false,
    'base' => 'select-border'
])
{{-- ========================================================= --}}
{{-- WRAPPER --}}
{{-- ========================================================= --}}
<div
     x-data="{ open: false }"
     x-on:focusin="open = true"
     x-on:blur="open = false"
     x-on:change="open = false"
     :class="{ 'open': open }"
     class="{{ $base }} {{ $error ? 'has-error' : '' }}"
>


    {{ $slot }}

    <x-heroicon
            name="chevron-right"
            size="md"
            variant="outline"
            class="select-arrow"
    />
</div>
