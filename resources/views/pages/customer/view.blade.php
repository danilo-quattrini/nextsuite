<?php

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelIdea\Helper\App\Models\_IH_Customer_C;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    protected string $paginationTheme = 'tailwind';

    #[Computed]
    public function customers(): _IH_Customer_C|LengthAwarePaginator|array
    {
        return Customer::getCustomersOwnedByUser();
    }
};
?>
<x-card.content-page-card
        title="Customer Skill Schemas"
        description="Choose the customer you want to create or change skill schema."
        :has-counter="true"
        :has-grid="true"
        :counter-title="Str::plural('Customer', count($this->customers) ?? 0)"
        :counter-value="$this->customers->total()"
>
    @foreach($this->customers as $customer)
        <x-user-card :user="$customer" href="skill-schema.create"/>
    @endforeach


    @if(count($this->customers) > 6)
        <x-slot:pagination>
            <div class="page-content__pagination">
                {{ $this->customers->links('components.pagination') }}
            </div>
        </x-slot:pagination>
    @endif
</x-card.content-page-card>
