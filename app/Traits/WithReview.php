<?php

namespace App\Traits;


use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

trait WithReview
{
    public bool $showReviewModal = false;
    public mixed $reviewableId;
    public string $reviewableType;

    #[Validate('required|string|min:5')]
    public string $review = '';

    #[Validate('required|integer|min:1|max:5')]
    public int $rating = 1;


    #[On('review-user')]
    public function openReviewModal(int $id, string $type): void
    {
        $model = match ($type) {
            'customer' => Customer::findOrFail($id),
            default => throw new \InvalidArgumentException('Invalid reviewable type'),
        };

        $this->review($model);
    }

    public function review($model): void
    {
        $this->reviewableId = $model->id;
        $this->reviewableType = get_class($model);

        $this->rating = 1;
        $this->review = '';

        $this->showReviewModal = true;
    }

    public function saveReview()
    {
        $this->validate();

        $model = ($this->reviewableType)::findOrFail($this->reviewableId);

        $model->reviews()->create([
            'rating' => $this->rating,
            'comment' => $this->review,
            'author_id' => auth()->id()
        ]);

        $this->reset([
            'showReviewModal',
            'reviewableId',
            'reviewableType'
        ]);

        return $this->redirect(route('customer.list'));
    }
}
