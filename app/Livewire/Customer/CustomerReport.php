<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerReport extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'tailwind';

    public function render()
    {
        return view('livewire.customer.customer-report', [
            'customers' => Customer::with('skills')
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->paginate(6)
        ]);
    }
}
