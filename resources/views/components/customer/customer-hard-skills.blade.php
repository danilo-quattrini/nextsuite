<?php

use App\Models\Customer;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public ?Customer $customer = null;
    public ?Collection $hardSkills = null;

    public function mount(): void
    {
        $this->loadSkill();
        $this->getHardSkills();
    }

    public function loadSkill(): void
    {
        $this->customer->load('skills.category.fields');
    }

    public function getHardSkills(): void
    {
        $this->hardSkills = $this->customer->skills
            ->filter(fn($skill) => $skill->category?->type->value !== 'soft_skill')
            ->values();
    }
};
?>

<x-card.card-container title="Hard Skills">
    {{--  COUNTER  --}}
    @if($hardSkills->count() > 0)
        <x-tag variant="white" size="lg">
            {{ $hardSkills->count() }} {{ Str::plural('skill', $hardSkills->count()) }}
        </x-tag>
    @endif

    <x-slot:action>
        @livewire('skill-modal', ['hideSoftSkills' => true])
    </x-slot:action>
    @if($hardSkills->isEmpty())
        <x-empty-state
                icon="academic-cap"
                message="No hard skills added yet"
                description="Add technical skills and expertise to showcase capabilities"
        />
    @else
        <div class="skills-grid">
            @foreach($hardSkills as $skill)
                <x-card.skill-card :skill="$skill"/>
            @endforeach
        </div>
    @endif
</x-card.card-container>