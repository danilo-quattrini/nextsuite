@props([
    'review' => null
    ])
<div class="review-card">
    <div class="review-card__header">
        <div class="review-card__author">
            <x-profile-image
                    :name="$review->author->full_name"
                    size="custom"
                    class="size-12"
            />
            <div class="flex flex-col">
                <span class="font-medium text-sm">{{ $review->author->full_name }}</span>
                <span class="text-xs text-primary-grey">{{ $review->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="rating-badge">
            <x-heroicon name="star" variant="solid" class="rating-badge__icon"/>
            <span class="rating-badge__value">{{ number_format($review->rating, 1) }}</span>
        </div>

    </div>

    @if($review->comment)
        <p class="review-card__comment">
            {{ $review->comment }}
        </p>
    @endif
</div>
