<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Livewire\Component;

class ShowCustomerReport extends Component
{
    public Customer $customer;
    public ?float $softSkillsAverage = null;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer
            ->load('skills.category')
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating');
    }

    public function render()
    {
        return view('livewire.customer.show-customer-report');
    }
}
