<x-card.content-page-card
        title="Reports"
        description="Select a customer that you want to see its report"
        :has-counter="true"
        :has-grid="true"
        :counter-title="Str::plural('Customer', count($this->customers) ?? 0)"
        :counter-value="$this->customers->total()"
>
    @foreach($this->customers as $customer)
        <x-user-card :user="$customer" href="report.show"/>
    @endforeach

    @if($this->customers->total() > 6)
        <x-slot:pagination>
            <div class="page-content__pagination">
                {{ $this->customers->links('components.pagination') }}
            </div>
        </x-slot:pagination>
    @endif

</x-card.content-page-card>