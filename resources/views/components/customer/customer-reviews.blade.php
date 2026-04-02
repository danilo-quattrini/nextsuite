<?php

use App\Models\Customer;
use App\Traits\WithReview;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

new #[Lazy]
class extends Component {

    use WithReview;

    public ?Customer $customer = null;
    public int $visibleSection = 2;

    #[Computed]
    public function reviews()
    {
        return $this->customer->reviews()
            ->with('author')
            ->latest()
            ->get();
    }

    #[Computed]
    public function reviewStats(): array
    {
        return [
            'count' => $this->customer->reviews_count ?? $this->customer->reviews()->count(),
            'average' => $this->customer->reviews_avg_rating ?? $this->customer->reviews()->avg('rating'),
        ];
    }

    public function showMore(): void
    {
        $this->visibleSection += count($this->reviews) - $this->visibleSection;
    }

    public function showLess(): void
    {
        $this->visibleSection = 2;
    }
    public function placeholder(): string
    {
        return <<<'HTML'
                <x-card.card-container title="Reviews">
                    <div class="flex items-center justify-center py-8">
                        <x-spinner size="lg" label="Loading reviews"/>
                    </div>
                </x-card.card-container>
        HTML;
    }
};
?>

<x-card.card-container title="Reviews">
    <x-slot:action>
        <x-button
                wire:click.prevent="$dispatch('review-user', { id: {{ $customer->id }}, type: 'customer' })"
        >
            <x-heroicon
                    name="pencil"
            />
            Review
        </x-button>
    </x-slot:action>

    @forelse($this->reviews->slice(0, $visibleSection) as $review)
        <x-card.review-card :review="$review"/>
    @empty
        <x-empty-state
                icon="star"
                message="No reviews yet"
                description="Be the first to review this customer"
        />
    @endforelse
    @if(count($this->reviews) > $visibleSection)
        <button
                wire:click.preserve-scroll="showMore"
                wire:transition
                @click.stop
                class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
        >
            Show more ({{ count($this->reviews) - $visibleSection }} remaining)
        </button>
    @elseif(count($this->reviews) !== 1 && $this->reviews->isNotEmpty())
        <button
                wire:click.preserve-scroll="showLess"
                wire:transition
                @click.stop
                class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
        >
            Show less
        </button>
    @endif
</x-card.card-container>
