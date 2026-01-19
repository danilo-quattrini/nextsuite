<?php

namespace App\Livewire;

use App\Services\NationalityService;
use Livewire\Component;

class Nationalities extends Component
{
    public NationalityService $service;

    public function mount(NationalityService $service): void
    {
        $this->service = $service;
    }

    public function getNationalitiesProperty(): array
    {
        return $this->service->all();
    }
}
