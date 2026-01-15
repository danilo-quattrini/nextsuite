<?php

namespace App\Livewire\Template;

use Illuminate\Support\Collection;
use Livewire\Component;

class CreateTemplate extends Component
{
    public Collection $templates;

    public function mount(): void
    {

    }

    public function render()
    {
        return view('livewire.template.create-template');
    }
}
