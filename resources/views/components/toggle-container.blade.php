{{--
    TOGGLE CONTAINER
    ================
    A label wrapper that composes an external checkbox input with
    its visual mark and label text. Useful when the checkbox input
    comes from a separate component (x-form.checkbox in input-only mode).

    CRITICAL DOM ORDER: $element (the <input>) MUST come before
    $span (.ds-checkbox-mark) — the CSS uses the adjacent sibling
    selector `input:checked + .ds-checkbox-mark`. Reversing them
    will break the checked visual state.

    USAGE:
        <x-toggle-container description="Optional helper text">
            <x-slot:element>
                <x-form.checkbox id="remember_me" name="remember" size="sm" />
            </x-slot:element>
            <x-slot:span>
                <span class="ds-checkbox-mark"></span>
            </x-slot:span>
            Remember me
        </x-toggle-container>

    PROPS
    -----
    description    string    Optional helper text shown below the label.
--}}


@props([
    'description' => null
])
<label class="ds-checkbox-container">
    {{ $element }}   {{-- Must be first: the <input> --}}
    {{ $span }}      {{-- Must be second: the .ds-checkbox-mark span --}}
    <span class="ds-checkbox-label">
        {{ $slot }}
        @if($description)
            <span class="ds-checkbox-description">{{ $description }}</span>
        @endif
    </span>
</label>