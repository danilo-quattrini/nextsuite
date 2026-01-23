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

    public ?string $bladeTemplate = null;

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
                'settings.*' => ['required', 'string'],
            ]
        ];
    }

    public function send(): void
    {
        if($this->step === 2) {
            $this->validate();

            $template = Template::create([
                'name' => $this->name,
                'type' => $this->type,
                'category' => $this->category,
                'structure' => $this->structure,
                'settings' => $this->settings,
                'blade_template' => $this->bladeTemplate,
                'is_active' => false
            ]);

            $this->redirectRoute('template.layout', $template);
        }
    }
}
