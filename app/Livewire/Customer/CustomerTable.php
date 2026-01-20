<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Traits\DeleteModal;
use App\Traits\WithReview;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;
    use WithReview;
    use DeleteModal;
    public bool $showDeleteModal = false;
    public ?int $customerToDelete = null;

    protected string $paginationTheme = 'tailwind';


    #[On('review-user')]
    public function openReviewModal(int $id, string $type): void
    {
        $model = match ($type) {
            'customer' => Customer::findOrFail($id),
            default => throw new \InvalidArgumentException('Invalid reviewable type'),
        };

        $this->review($model);
    }

    public function render()
    {
        return view('livewire.customer.customer-table', [
            'customers' => Customer::with('skills')
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->paginate(5)
        ]);
    }
}
