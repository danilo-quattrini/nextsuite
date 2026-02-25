<?php

namespace App\Traits;


use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

trait WithReview
{
    public bool $showModal = false;
    public mixed $reviewableId;
    public string $reviewableType;

    #[Validate('required|string|min:5')]
    public string $review = '';

    #[Validate('required|integer|min:1|max:5')]
    public int $rating = 1;

    /**
     * Open the review modal when it has been called
     */
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

        $this->showModal = true;
    }

    public function saveReview(): void
    {
        $this->validate();

        $model = ($this->reviewableType)::findOrFail($this->reviewableId);

        $model->reviews()->create([
            'rating' => $this->rating,
            'comment' => $this->review,
            'author_id' => auth()->id()
        ]);

       $this->closeModal();

        session()->flash('info', 'Left the review for the user: ' .  $model->full_name ?:  'User');

        $this->redirect(route('customer.list'), navigate: true);
    }

    /**
     * Close the modal
     */
    #[On('close-modal')]
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset all form fields
     */
    private function resetForm(): void
    {
        $this->reset([
            'showModal',
            'reviewableId',
            'reviewableType'
        ]);

        $this->resetValidation();
    }
}
