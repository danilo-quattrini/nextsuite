@props([
    'title' => 'Example'
])
<div class="space-y-4 pb-4">
    <p class="font-semibold uppercase tracking-wideborder-b-black">
        {{ $title }}
    </p>
    {{ $slot }}
</div>