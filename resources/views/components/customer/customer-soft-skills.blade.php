<?php

use App\Domain\Skill\Services\Chart\ChartFactory;
use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SkillSchemaService;
use App\Domain\Skill\Services\SoftSkillChartService;
use App\Models\Customer;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?Customer $customer = null;
    public ?Collection $softSkills = null;
    public ?Collection $customerSkills = null;

    public ?float $softSkillsAverage = null;
    public bool $skillSchemaExists = false;

    public function mount(): void
    {
        $this->checkSkillSchema();
        $this->getSoftSkills();
    }

    #[Computed]
    public function checkSkillSchema(): void
    {
        $service = new SkillSchemaService()->for($this->customer);
        if ($service->isSkillSchemaExists()) {
            $this->customerSkills = $service->getSkillsFromSchema();
            $this->skillSchemaExists = true;
        } else {
            $this->loadSkill();
        }
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
        $this->loadSkill();
        $this->getSoftSkills();
    }
};
?>

<x-card.card-container
        title="Soft Skills"
        subtitle="Has a skill schema: {{ $skillSchemaExists ? 'yes' : 'no' }}"
>
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
            <x-button href="{{ route('skill-schema.create', $customer) }}" size="large">
                Create Skill
            </x-button>
        </div>
    @else
        <livewire:card.chart-card :data="$softSkills"/>
    @endif
</x-card.card-container>