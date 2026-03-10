@props([
    'icon' => 'inbox',
    'message',
    'description' => null
])

<div class="empty-state">
    <x-heroicon :name="$icon" variant="outline" class="empty-state__icon"/>
    <p class="empty-state__message">{{ $message }}</p>
    @if($description)
        <p class="empty-state__description">{{ $description }}</p>
    @endif
    <div class="empty-state__action">
        {{ $action ?? null }}
    </div>
</div>