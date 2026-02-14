<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelIdea\Helper\App\Models\_IH_Customer_C;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerReport extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'tailwind';

    #[Computed]
    public function customers(): _IH_Customer_C|LengthAwarePaginator|array
    {
        return Customer::with('skills')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate(6);
    }
}
