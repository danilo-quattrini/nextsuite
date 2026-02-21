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
};
?>

<x-card.card-container title="Reviews">
    <x-slot:action>
        <x-button
                type="button"
                wire:click.prevent="$dispatch('review-user', { id: {{ $customer->id }}, type: 'customer' })"
                variant="warning"
        >
            Write Review
        </x-button>
    </x-slot:action>

    @forelse($customer->reviews as $review)
        <x-card.review-card :review="$review"/>
    @empty
        <x-empty-state
                icon="star"
                message="No reviews yet"
                description="Be the first to review this customer"
        />
    @endforelse
</x-card.card-container>
