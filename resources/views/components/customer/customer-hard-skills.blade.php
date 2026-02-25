<?php

use App\Domain\Skill\Services\SkillAssignmentService;
use App\Models\Customer;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Lazy]
class extends Component {
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

    #[On('skill-added')]
    public function addSkillToCustomer(int $skillId, int $skillLevel, int|null $skillYears): void
    {
        $skill = Skill::find($skillId);
        if (!$skill || $skill->isSoftSkill()) {
            return;
        }

        try {
            $user = Auth::user();
            app(SkillAssignmentService::class)->assign(
                $this->customer,
                $user,
                $skillId,
                $skillLevel,
                $skillYears
            );
            $this->updateHardSkillModel();

            session()->flash('status', 'Skill added successfully!');
        } catch (Exception $e) {
            session()->flash('error', 'Failed to add skill: '.$e->getMessage());
        }
    }

    public function updateHardSkillModel(): void
    {
        $this->loadSkill();
        $this->getHardSkills();
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
        <x-button
                size="auto"
                wire:click="$dispatch('open-hard-skill-modal')"
        >
            <x-heroicon name="plus"/>
            New Skill
        </x-button>
    </x-slot:action>

    <livewire:skill-modal :user="$customer"/>
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
