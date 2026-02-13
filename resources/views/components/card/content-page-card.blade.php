@props([
    'title' => 'Default Title',
    'description' => 'Default Description',
    'hasGrid' => false,
    'hasCounter' => false,
    'counterTitle' => 'User',
    'counterValue' => 0
])
<div
        class="page-content__container"
>
    <div class="page-content__hero">
        <div class="page-content__hero-inner">
            <div class="page-content__hero-row">
                <div class="page-content__hero-copy">
                    <h2 class="page-content__title">
                        {{ __($title) }}
                    </h2>
                    <p class="page-content__subtitle">
                        {{__($description)}}
                    </p>
                </div>
                @if($hasCounter)
                    <div class="page-content__stats">
                        <div class="page-content__stat-card">
                            <p class="page-content__stat-label">{{ $counterTitle }}</p>
                            <p class="page-content__stat-value">{{ $counterValue }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="page-content__body">
        <div @if($hasGrid) class="page-content__grid" @endif >
                {{ $slot }}
        </div>
        {{ $pagination ?? null}}
    </div>
</div>
