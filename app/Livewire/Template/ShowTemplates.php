<?php

namespace App\Livewire\Template;

use App\Models\Template;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowTemplates extends Component
{
    public Collection $templates;

    public function mount(): void
    {
        $this->templates = Template::with('sections')->get();
    }

    public function render()
    {
        return view('livewire.template.show-templates');
    }
}
