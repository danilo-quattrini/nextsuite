<?php

namespace App\Livewire\Customer;

use App\Domain\Skill\Services\Chart\ChartFactory;
use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SoftSkillChartService;
use App\Models\Customer;
use App\Traits\DeleteModal;
use App\Traits\WithReview;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowCustomer extends Component
{
    use WithReview;
    use DeleteModal;

    public Customer $customer;
    public ?Collection $customerSkills = null;
    public ?Collection $softSkills = null;
    public ?Collection $fieldSkills = null;
    public ?Collection $fields = null;
    public ?float $softSkillsAverage = null;
    public array $softSkillChartData = [];

    public function mount(Customer $customer): void
    {
        $this->customer->load('skills.category.fields', 'attributes');

        $this->customerSkills = $this->customer->skills;

        $this->fieldSkills = $this->getFieldSkills();


        $this->fields = $this->getFields();
        $this->getSoftSkills($customer);
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

        $this->customer->load('skills.category.fields');
        $this->customerSkills = $this->customer->skills;
        $this->fields = $this->getFields();
        $this->fieldSkills = $this->getFieldSkills();

        $this->getSoftSkills($this->customer);
    }

    public function buildSoftSkillChart(string $categoryName, array $labels, array $data): Builder
    {
        $chart = ChartFactory::make('bar');
        $this->softSkillChartData = $chart->buildDataset($labels, $data);

        return $chart->buildChart($categoryName, $labels, $data)
            ->livewire()
            ->model('softSkillChartData');
    }

    public function getSoftSkills(Customer $customer): void
    {
        $service = app(SoftSkillChartService::class);
        $skillsByCategory = $service->buildSoftSkills($customer);

        $this->softSkills = $skillsByCategory;
        $this->softSkillsAverage = $service->overallAverage($skillsByCategory);
    }

    public function render()
    {
        return view('livewire.customer.show-customer');
    }

    public function getFields()
    {
        return $this->customerSkills
            ->flatMap(fn($skills) => $skills->category?->fields ?? collect())
            ->unique('id')
            ->values();
    }

    public function getFieldSkills()
    {
        return $this->customerSkills
            ->filter(fn($skill) => $skill->category?->type->value !== 'soft_skill')
            ->values();
    }
}
