<?php

use App\Models\Attribute;
use App\Domain\Attribute\Services\AttributeAssignableService;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Lazy]
class extends Component {

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

    public function placeholder(): string
    {
        return <<<'HTML'
                <x-card.card-container title="Attributes">
                    <div class="flex items-center justify-center py-8">
                        <x-spinner size="lg" label="Loading attributes"/>
                    </div>
                </x-card.card-container>
        HTML;
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
        <x-empty-state
                icon="adjustments-horizontal"
                message="No attribute yet"
                description="Add an attribute to this customer"
        />
    @endforelse
</x-card.card-container>
