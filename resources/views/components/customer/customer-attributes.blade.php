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

    #[On('attribute-added')]
    public function addAttributeToCustomer(Attribute $attribute, mixed $value): void
    {
        $this->isLoading = true;

        app(AttributeAssignableService::class)->assign(
            $this->customer,
            $attribute,
            $value
        );

        $this->updateAttribute();

        $this->isLoading = false;
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

    #[On('attribute-updated')]
    public function updatedAttribute(
        ?int $originalAttributeId,
        Attribute $attribute,
        $value
    ): void
    {
        $this->isLoading = true;

        $service = app(AttributeAssignableService::class);

        if($originalAttributeId === $attribute->id){
            $service->edit(
                model: $this->customer,
                attribute: $attribute,
                value: $value
            );
        }else{
            $service->replace(
                model: $this->customer,
                oldAttributeId: $originalAttributeId,
                newAttribute: $attribute,
                newValue: $value
            );
        }


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
                <x-button
                        size="auto"
                        wire:click="$dispatch('open-add-attribute')"
                >
                    <x-heroicon size="lg" name="plus"/>
                </x-button>
            </x-slot:action>
        @endif

        @forelse($userAttributes as $attribute)
            <x-card.card-container
                    title="{{ $attribute->name}}"
                    size="lg"
                    card-size="sm"
            >
                <x-slot:action>
                    {{-- DELETE AND EDIT BUTTONS --}}
                    <div class="flex justify-between items-center gap-sm">
                        <x-button
                                variant="disable"
                                size="auto"
                                wire:click="$dispatch('open-edit-attribute',{
                                    attributeId: {{ $attribute->id }},
                                    userId: {{ $customer->id }}
                                })"
                        >
                            <x-heroicon name="pencil-square" size="lg"/>
                        </x-button>
                        <x-button
                                variant="error"
                                size="auto"
                                wire:click="removeAttribute({{ $attribute->id }})"
                        >
                            <x-heroicon name="trash" size="lg"/>
                        </x-button>
                    </div>
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
                    <x-button
                            size="auto"
                            wire:click="$dispatch('open-add-attribute')"
                    >
                        <x-heroicon size="lg" name="plus"/>
                        New attribute
                    </x-button>
                </x-slot:action>
            </x-empty-state>
        @endforelse
    @endif

    @livewire('attribute.attribute-modal')
</x-card.card-container>
