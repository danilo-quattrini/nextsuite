<x-card.content-page-card
        title="Reports"
        description="Select a customer that you want to see its report"
        :has-counter="true"
        :has-grid="true"
        :counter-title="Str::plural('Customer', count($this->customers) ?? 0)"
        :counter-value="$this->customers->total()"
>
    @forelse($this->customers as $customer)
        <x-user-card :user="$customer" href="report.show"/>
    @empty
        <x-card.card-container
                class="col-span-3"
                card-size="lg"
        >
            <x-empty-state
                    icon="user"
                    message="No customer has been created or have been found"
                    description="You should create a new customer to generate a report"
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

    @if($this->customers->total() > 6)
        <x-slot:pagination>
            <div class="page-content__pagination">
                {{ $this->customers->links('components.pagination', data: ['scrollTo' => false]) }}
            </div>
        </x-slot:pagination>
    @endif

</x-card.content-page-card>