<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Traits\DeleteModal;
use App\Traits\WithReview;
use Illuminate\View\View;
use Livewire\Component;

class ShowCustomer extends Component
{
    use WithReview;
    use DeleteModal;

    public Customer $customer;

    public function render() : View
    {
        return view('livewire.customer.show-customer');
    }
}
