<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Traits\WithReview;
use http\Exception\InvalidArgumentException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;
    use WithReview;
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

    public function confirmDelete(int $customerId): void
    {
        $this->customerToDelete = $customerId;
        $this->showDeleteModal = true;
    }

    public function deleteCustomer(): void
    {
        Customer::find($this->customerToDelete)->delete();

        $this->reset(['showDeleteModal', 'customerToDelete']);

        session()->flash('success', 'Customer deleted successfully.');
    }
    public function render()
    {
        return view('livewire.customer.customer-table', [
            'customers' => Customer::with('skills')->paginate(10)
        ]);
    }
}
