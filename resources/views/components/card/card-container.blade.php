@props([
    'title' => 'Title'
])
<div class="card-container">
    <div class="card-head">
        <h3>{{ $title }}</h3>
        {{ $action ?? null }}
    </div>
    {{ $slot }}
</div>
