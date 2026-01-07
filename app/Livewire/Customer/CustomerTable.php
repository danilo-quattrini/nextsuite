<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;

    public bool $showDeleteModal = false;
    public ?int $customerToDelete = null;


    protected string $paginationTheme = 'tailwind';

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
