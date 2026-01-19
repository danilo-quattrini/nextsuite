<?php

namespace App\Livewire\Template;

use App\Enums\DocumentCategory;
use App\Enums\DocumentType;
use App\Traits\WithStep;
use Livewire\Component;

class CreateTemplate extends Component
{
    use WithStep;

    public string $name = '';
    public string $type = '';
    public string $category = '';
    public array $settings = [];
    public ?string $bladeTemplate = null;
    public array $structure = [];

    public array $templateCategory;
    public array $templateType;

    public function mount(): void
    {
        $this->templateType = DocumentType::toArray();
        $this->templateCategory = DocumentCategory::toArray();
    }

    public function render()
    {
        return view('livewire.template.create-template');
    }

    public function updatedCategory(): void
    {
        $type = DocumentCategory::from($this->category);

        $defaults = $type->defaults();

        $this->structure = $defaults['structure'];
        $this->settings = $defaults['settings'];
        $this->bladeTemplate = $defaults['blade_template'];
    }

    protected function stepRules(): array
    {
        return [
            1 => [
                'name' => ['required', 'string', 'min:5'],
                'type' => ['required']
            ],
            2 => [
                'category' => ['required'],
                'settings' => ['required', 'array'],
                'settings.*' => ['required'],
            ]
        ];
    }
}
