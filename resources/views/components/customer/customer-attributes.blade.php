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

    public bool $isLoading = false;

    public function mount(): void
    {
        $this->updateAttribute();
    }

    #[On('attribute-selected')]
    public function addAttributeToCustomer(Attribute $attribute, mixed $value): void
    {
        app(AttributeAssignableService::class)->assign(
            $this->customer,
            $attribute,
            $value
        );

        $this->updateAttribute();
    }

    #[On('remove-attribute')]
    public function removeAttribute(int $attributeId): void
    {
        $this->isLoading = true;

        app(AttributeAssignableService::class)->remove(
            model: $this->customer,
            id: $attributeId
        );

        $this->updateAttribute();

        $this->isLoading = false;
    }

    public function placeholder(): string
    {
        return <<<'HTML'
                <x-card.card-container title="Attributes">
                     <div class="skills-loading">
                        <x-spinner size="lg" />
                        <span class="skills-loading__text">Updating attributes...</span>
                    </div>
                </x-card.card-container>
        HTML;
    }

    public function updateAttribute(): void
    {
        $this->customer->load('attributes');
        $this->userAttributes = $this->customer->attributes;
    }
};
?>

<x-card.card-container title="Attributes">

    @if($isLoading)
        <div class="skills-loading">
            <x-spinner size="lg" />
            <span class="skills-loading__text">Updating attributes...</span>
        </div>
    @else
        @if($userAttributes->isNotEmpty())
            <x-slot:action>
                @livewire('attribute.attribute-modal')
            </x-slot:action>
        @endif

        @forelse($userAttributes as $attribute)
            <x-card.card-container
                    title="{{ $attribute->name}}"
                    size="lg"
                    card-size="sm"
            >
                <x-slot:action>
                    <x-button
                            variant="error"
                            size="auto"
                            wire:click.prevent="removeAttribute({{ $attribute->id }})"
                    >
                        <x-heroicon name="trash" size="lg"/>
                    </x-button>
                </x-slot:action>
                <span
                        class="text-lg font-light leading-none"
                >
                    {{ trim(ucfirst($attribute->pivot?->value)) }}
                </span>
            </x-card.card-container>
        @empty
            <x-empty-state
                    icon="adjustments-horizontal"
                    message="No attribute yet"
                    description="Add an attribute to this customer"
            >
                <x-slot:action>
                    @livewire('attribute.attribute-modal')
                </x-slot:action>
            </x-empty-state>
        @endforelse
    @endif
</x-card.card-container>
