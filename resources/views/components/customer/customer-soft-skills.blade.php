<?php

use App\Domain\Skill\Services\Chart\ChartFactory;
use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SoftSkillChartService;
use App\Models\Customer;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?Customer $customer = null;
    public ?Collection $softSkills = null;
    public ?Collection $customerSkills = null;

    public ?float $softSkillsAverage = null;

    public function mount(): void
    {
        $this->loadSkill();
        $this->getSoftSkills();
    }

    public function loadSkill(): void
    {
        $this->customer->load('skills.category.fields');
        $this->customerSkills = $this->customer->skills;
    }

    #[On('skill-selected')]
    public function addSkillToCustomer(int $skillId, int $skillLevel, int|null $skillYears): void
    {
        $user = Auth::user();

        app(SkillAssignmentService::class)->assign(
            $this->customer,
            $user,
            $skillId,
            $skillLevel,
            $skillYears
        );

        $this->updateSoftSkillModel();
    }
    public function getSoftSkills(): void
    {
        $service = app(SoftSkillChartService::class);
        $skillsByCategory = $service->buildSoftSkills($this->customer);

        $this->softSkills = $skillsByCategory;
        $this->softSkillsAverage = $service->overallAverage($skillsByCategory);
    }

    public function updateSoftSkillModel(): void
    {
        $this->customer->load('skills.category.fields');
        $this->customerSkills = $this->customer->skills;
        $this->getSoftSkills();
    }
};
?>

<x-card.card-container title="Soft Skills">
    {{--  COUNTER  --}}
    @if($softSkills->count() > 0)
        <x-tag variant="white" size="lg">
            {{ $softSkills->count() }} {{ Str::plural('skill', $softSkills->count()) }}
        </x-tag>
    @endif

    <x-slot:action>
        @livewire('skill-modal', ['hideFieldSkills' => true])
    </x-slot:action>

    @if($softSkills->isEmpty())
        <x-empty-state
                icon="star"
                message="No soft skill added yet"
                description="Add a skill schema to this customer"
        />
        <div class="flex justify-center items-center">
            <x-button href="{{ route('skill-schema.create', $customer) }}" size="large" >
                Create Skill
            </x-button>
        </div>
    @else
        <livewire:card.chart-card :data="$softSkills"/>
    @endif
</x-card.card-container>