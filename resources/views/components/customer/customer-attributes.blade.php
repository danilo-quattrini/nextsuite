<?php

use App\Models\Attribute;
use App\Domain\Attribute\Services\AttributeAssignableService;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {

    public ?Customer $customer = null;
    public ?Collection $userAttributes = null;

    public function mount(): void
    {
        $this->userAttributes = $this->customer->attributes;
    }

    #[On('attribute-selected')]
    public function addAttributeToCustomer(Attribute $attribute, mixed $value): void
    {
        app(AttributeAssignableService::class)->assign(
            $this->customer,
            $attribute,
            $value
        );

        $this->userAttributes = $this->customer->attributes;
    }
};
?>

<x-card.card-container title="Attributes">
    <x-slot:action>
        @livewire('attribute.attribute-modal')
    </x-slot:action>

    @forelse($userAttributes as $attribute)
        <p><strong>{{ $attribute->name}}</strong>: {{ $attribute->pivot?->value }}</p>
    @empty
        <p class="text-sm text-primary-grey">
            No attributes yet.
        </p>
    @endforelse
</x-card.card-container>