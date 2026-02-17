<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerReport extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'tailwind';

    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        return Customer::getCustomersWithReviews();
    }
}
