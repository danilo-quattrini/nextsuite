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

    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    #[Computed(cache: false)]
    public function customers(): LengthAwarePaginator
    {
        return Customer::getCustomersOwnedByUser(page: $this->getPage());
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
    @forelse($this->customers as $customer)
        <x-user-card :user="$customer" href="skill-schema.create"/>
    @empty
        <x-card.card-container
                class="col-span-3"
                card-size="lg"
        >
            <x-empty-state
                    icon="user"
                    message="No customer has been created or have been found"
                    description="You should create a new customer to assign a skill schema"
            >
                <x-slot:action>
                    <x-button
                            size="full"
                            variant="white"
                            href="{{ route('dashboard') }}"
                    >
                        <x-heroicon name="home"/>
                        Home
                    </x-button>
                    <p> or </p>
                    <x-button
                            size="full"
                            href="{{route('customer.create')}}"
                    >
                        <x-heroicon name="user"/>
                        Create Customer
                    </x-button>
                </x-slot:action>
            </x-empty-state>
        </x-card.card-container>
    @endforelse


    @if(count($this->customers) > 6)
        <x-slot:pagination>
            <div class="page-content__pagination">
                {{ $this->customers->links('components.pagination') }}
            </div>
        </x-slot:pagination>
    @endif
</x-card.content-page-card>
