@props(['code' => null, 'name' => null, 'class' => 'w-5 h-5'])

@if($code && preg_match('/^[A-Za-z]{2}$/', $code))
    <x-dynamic-component
            :component="'flag-country-' . strtolower($code)"
            :class="$class"
    />
@endif