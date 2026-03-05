@props([
    'title' => 'Example'
])
<div class="space-y-4 border-b border-outline-grey/60 pb-3">
    <p class="font-semibold uppercase tracking-wideborder-b-black">
        {{ $title }}
    </p>
    {{ $slot }}
</div>