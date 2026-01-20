<?php

namespace App\Livewire\Template;

use App\Enums\DocumentCategory;
use App\Enums\DocumentType;
use App\Models\Template;
use App\Traits\WithStep;
use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateTemplate extends Component
{
    use WithStep;

    #[Validate('required|min:5')]
    public string $name = '';
    #[Validate('required|string')]
    public string $type = '';
    #[Validate('required|string')]
    public string $category = '';
    #[Validate('required|array')]
    public array $settings = [];
    public array $paperSizes = [];

    #[Validate('required|string|nullable')]
    public ?string $bladeTemplate = null;
    #[Validate('required|array')]
    public array $structure = [];

    public array $templateCategory;
    public array $templateType;

    public function mount(): void
    {
        $this->templateType = DocumentType::toArray();
        $this->templateCategory = DocumentCategory::toArray();
        $this->paperSizes = CPDF::$PAPER_SIZES;

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

    public function submit(): void
    {
        $this->validate();

        Template::create([
            'name' => $this->name,
            'type' => $this->type,
            'category' => $this->category,
            'structure' => json_encode($this->structure),
            'settings' => json_encode($this->settings),
            'blade_template' => $this->bladeTemplate,
        ]);

        $this->redirect(route('template.index'));
    }
}
