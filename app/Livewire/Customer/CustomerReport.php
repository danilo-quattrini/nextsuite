<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerReport extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'tailwind';

    #[Computed(cache: false)]
    public function customers(): LengthAwarePaginator
    {
        return Customer::getCustomersOwnedByUser(page: $this->getPage());
    }

    public function render(): View
    {
        return view('livewire.customer.customer-report');
    }
}
