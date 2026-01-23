<?php

namespace App\Livewire\Template;

use App\Models\Template;
use App\Models\TemplateSection;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class LayoutEditor extends Component
{
    public Template $template;

    public array $structure = [];
    public array $availableSections = [];

    public function mount(Template $template): void
    {
        abort_if($template->is_active, 403);


        $this->template = $template;
        $this->structure = $template->structure ?? [
            'pages' => [
                ['sections' => []]
            ]
        ];

        $this->availableSections = TemplateSection::with('template')->get()->toArray();
    }

    public function addSection(string $type): void
    {
        $this->structure['pages'][0]['sections'][] = [
            'type' => $type,
            'x' => 0,
            'y' => count($this->structure['pages'][0]['sections']) * 2,
            'w' => 6,
            'h' => 2,
        ];
    }

    public function save(): RedirectResponse
    {
        $this->template->update([
            'structure' => $this->structure,
            'is_active' => true,
        ]);

        return redirect()->route('template.index');
    }

    public function render()
    {
        return view('livewire.template.layout-editor');
    }
}
