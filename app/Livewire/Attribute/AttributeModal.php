<?php

namespace App\Livewire\Attribute;


use App\Enums\AttributeType;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;

class AttributeModal extends Component
{
    public bool $showAttributeModal = false;
    public ?int $selectedAttributeId = null;
    public ?int $selectedCategoryId = null;

    public Collection $categories;
    public Collection $customerAttributes;
    public ?Attribute $attribute = null;
    public mixed $value = null;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'selectedAttributeId' => ['required', 'exists:attributes,id'],
            'selectedCategoryId' => ['required', 'exists:categories,id'],
            'attribute' => ['required'],
            'value' => ['required']
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'selectedAttributeId.required' => 'Please select an attribute.',
            'selectedCategoryId.required' => 'Please set a category for this attribute.',
            'value.required' => 'Select at least one value.'
        ];
    }

    public function mount(): void
    {

        $this->categories = Category::with('attributes.category.fields')
            ->where('type' , '<>', 'soft_skill')
            ->get();
    }

    public function render()
    {
        return view('livewire.attribute.attribute-modal');
    }

    public function updatedSelectedCategoryId($categoryId): void
    {
        $this->customerAttributes = Attribute::where('category_id', $categoryId)->get();
        $this->selectedAttributeId = null;
    }

    public function updatedSelectedAttributeId($attributeId): void
    {
        $this->attribute = Attribute::findOrFail($attributeId);
        $this->value = null; // reset value when attribute changes
    }

    public function attributeInputConfig(): array
    {
        if (! $this->attribute) {
            return [];
        }

        return match ($this->attribute->type) {
            AttributeType::STRING => [
                'component' => 'input',
                'type' => 'text',
            ],

            AttributeType::NUMBER => [
                'component' => 'input',
                'type' => 'number',
            ],

            AttributeType::DATE => [
                'component' => 'input',
                'type' => 'date',
            ],

            AttributeType::BOOLEAN => [
                'component' => 'radio',
                'options' => $this->attribute->options,
            ],

            AttributeType::SELECT => [
                'component' => 'select',
                'options' => $this->attribute->options,
            ],
        };
    }

    public function addAttribute(): void
    {
        $this->validate();
        
        $this->dispatch('attribute-selected', attribute: $this->attribute, value: $this->value);
        
        $this->reset([
            'showAttributeModal',
            'selectedAttributeId',
            'selectedCategoryId',
            'attribute',
            'value',
        ]);

    }

}
