<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Traits\DeleteModal;
use App\Traits\WithReview;
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
