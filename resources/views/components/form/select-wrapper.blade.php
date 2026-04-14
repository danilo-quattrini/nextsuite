{{--
    SELECT WRAPPER
    ==============
    The styled border shell around x-form.select-element.
    Manages open/closed state, has-value tracking, keyboard search,
    and error/disabled visual states.

    USAGE — simple:
        <x-form.select-wrapper>
            <x-form.select-element name="role" placeholder="Select role">
                <x-slot:options>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </x-slot:options>
            </x-form.select-element>
        </x-form.select-wrapper>

    USAGE — with error and disabled:
        <x-form.select-wrapper
            :error="$errors->has('form.role')"
            :disable="$user->cannot('change_role')"
        >
            ...
        </x-form.select-wrapper>

    PROPS
    -----
    error      bool   Applies has-error ring (default: false)
    disable    bool   Applies is-disable state (default: false)

    NOTES
    -----
    - Alpine's `hasValue` is written by select-element's @change handler.
      The wrapper reads it for the has-value CSS class.
    - `open` tracks focus-within so the chevron rotates correctly.
    - Keyboard search (type to jump to matching option) is handled
      by the `handleTypeSearch` method.
--}}
@props([
    'error' => false,
    'base' => 'select-border',
    'disable' => false,
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
     class="{{ $base }} {{ $error ? 'has-error' : '' }} {{ $disable ? 'is-disable' : '' }}"
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
