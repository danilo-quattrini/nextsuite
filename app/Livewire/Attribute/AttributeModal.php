<?php

namespace App\Livewire\Attribute;


use App\Enums\AttributeType;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class AttributeModal extends Component
{
    public bool $showModal = false;
    public string $mode = 'add';

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
        $this->categories = Category::getAllExceptSoftSkills();
    }

    public function render()
    {
        return view('livewire.attribute.attribute-modal');
    }

    /**
     * Open in add mode
     */
    #[On('open-add-attribute')]
    public function openForAdd(): void
    {
        $this->resetForm();
        $this->mode      = 'add';
        $this->showModal = true;
    }

    /**
     * Open in edit mode, pre-filling the current value
     */
    #[On('open-edit-attribute')]
    public function openForEdit(
        int $attributeId,
        int $userId
    ): void {
        $this->resetForm();
        $this->mode = 'edit';
        $this->attribute = Attribute::getAttributeById($attributeId);

        $this->value = Customer::find($userId)
            ->getAssignableAttribute(key: $attributeId)
            ?->pivot
            ?->value;

        $this->selectedCategoryId  = $this->attribute->category_id;
        $this->customerAttributes  = Category::getAttributesWithCategoryId($this->attribute->category_id);
        $this->selectedAttributeId = $this->attribute->id;

        $this->showModal = true;
    }

    public function updatedSelectedCategoryId($categoryId): void
    {
        $this->customerAttributes = Category::getAttributesWithCategoryId($categoryId);
        $this->selectedAttributeId = null;
    }

    public function updatedSelectedAttributeId($attributeId): void
    {
        $this->attribute = Attribute::getAttributeById($attributeId);
        $this->value = null;
    }

    public function attributeInputConfig(): array
    {
        if (!$this->attribute) {
            return [];
        }

        return match ($this->attribute->type) {
            AttributeType::STRING  => ['component' => 'input', 'type' => 'text'],
            AttributeType::NUMBER  => ['component' => 'input', 'type' => 'number'],
            AttributeType::DATE    => ['component' => 'input', 'type' => 'date'],
            AttributeType::BOOLEAN => ['component' => 'radio',  'options' => $this->attribute->options],
            AttributeType::SELECT  => ['component' => 'select', 'options' => $this->attribute->options],
        };
    }

    public function save(): void
    {
        $this->validate();

        match ($this->mode) {
            'add'  => $this->dispatch('attribute-added', attribute: $this->attribute, value: $this->value),
            'edit' => $this->dispatch('attribute-updated', attributeId: $this->attribute->id, value: $this->value),
        };

        $this->closeModal();

    }

    /**
     * Close the modal
     */
    #[On('close-modal')]
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset all form fields
     */
    private function resetForm(): void
    {
        $this->reset([
            'mode',
            'showModal',
            'selectedAttributeId',
            'selectedCategoryId',
            'attribute',
            'value',
        ]);

        $this->resetValidation();
    }
}
