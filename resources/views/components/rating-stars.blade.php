<?php

use App\Models\Skill;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Modelable;

new #[Lazy]
class extends Component {
    #[Modelable]
    public ?int $rating = 0;
    public string $size = 'sm';
    public bool $selectable = false;
    public int $max = 5;
    public bool $disabled = false;
    public string $name = 'rating';
    public string $ariaLabel = 'Rating';
    public ?string $id = null;

    #[On('filter-customer')]
    public function filter(array $skillIds): void
    {
        $this->dispatch('customer-filters-updated', skillIds: $skillIds, ratingStars: $this->rating);
    }
    #[On('clear-rating')]
    public function clear(): void
    {
        $this->rating = 0;
    }
};
?>

@php
    $max = max(1, (int) $max);
    $ratingValue = is_numeric($rating) ? (int) $rating : null;
    $ratingValue = ($ratingValue !== null && $ratingValue > 0) ? min($max, $ratingValue) : null;
    $baseId = $id ?? ('rating-stars-' . uniqid());
@endphp
<div class="flex items-center gap-2 my-3">
    @if(!$selectable && !$ratingValue)
        <p class="text-sm text-primary-grey">N.A</p>
    @else
        <div
                @class([
                    'flex items-center gap-1' => ! $selectable,
                    'flex flex-row-reverse justify-end gap-1' => $selectable,
                ])
                role="{{ $selectable ? 'radiogroup' : 'img' }}"
                aria-label="{{ $ariaLabel }}"
        >
            @if($selectable)
                @for($i = $max; $i >= 1; $i--)
                    <input
                            id="{{ $baseId }}-{{ $i }}"
                            type="radio"
                            name="{{ $name }}"
                            value="{{ $i }}"
                            class="peer sr-only"
                            wire:model.live="rating"
                            @disabled($disabled)
                            @checked($ratingValue === $i)
                            aria-label="{{ $i }} star"
                    />
                    <label
                            for="{{ $baseId }}-{{ $i }}"
                            @class([
                                'rounded-md p-1 transition' => true,
                                'cursor-pointer text-outline-grey hover:text-secondary-warning/80 hover:bg-secondary-warning/10 hover:scale-105' => ! $disabled,
                                'peer-checked:text-secondary-warning peer-checked:drop-shadow-sm' => true,
                                'peer-focus-visible:ring-2 peer-focus-visible:ring-secondary-warning/60 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-white' => ! $disabled,
                                'cursor-not-allowed opacity-50' => $disabled,
                            ])
                    >
                        <x-heroicon
                                name="star"
                                variant="solid"
                                :size="$size"
                        />
                    </label>
                @endfor
            @else
                @for($i = 1; $i <= $max; $i++)
                    <x-heroicon
                            name="star"
                            variant="solid"
                            :size="$size"
                            class="{{ $i <= $ratingValue ? 'text-secondary-warning' : 'text-outline-grey' }}"
                    />
                @endfor
            @endif
        </div>
    @endif
</div>
