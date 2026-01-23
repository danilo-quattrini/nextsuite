<?php

namespace App\Livewire\Template;

use App\Models\Template;
use App\Traits\DeleteModal;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class ShowTemplates extends Component
{
    use DeleteModal;
    public Collection $templates;

    public function mount(): void
    {
        $this->templates = Template::with('sections')->get();
    }

    public function render(): Factory | View
    {
        return view('livewire.template.show-templates');
    }
}
