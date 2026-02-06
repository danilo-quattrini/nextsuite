<?php

namespace App\Livewire\Customer;

use App\Domain\Attribute\Services\AttributeAssignableService;
use App\Domain\Skill\Services\Chart\ChartFactory;
use App\Domain\Skill\Services\SkillAssignmentService;
use App\Domain\Skill\Services\SoftSkillChartService;
use App\Models\Attribute;
use App\Models\Customer;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowCustomer extends Component
{
    public Customer $customer;

    public ?Collection $customerAttributes = null;
    public ?Collection $customerSkills = null;
    public ?Collection $softSkills = null;
    public ?Collection $fields = null;
    public ?float $softSkillsAverage = null;
    public array $softSkillChartData = [];

    public function mount(Customer $customer): void
    {

        $this->customer = $customer
            ->load('skills.category.fields')
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating');

        $this->customerAttributes = $this->customer->attributes;

        $this->customerSkills = $this->customer->skills->forPage(1, 6);

        $this->fields = $this->customerSkills
            ->flatMap(fn ($skills) => $skills->category?->fields ?? collect())
            ->unique('id')
            ->values();

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
        $this->fields = $this->customerSkills
            ->flatMap(fn ($skills) => $skills->category?->fields ?? collect())
            ->unique('id')
            ->values();

        $this->getSoftSkills($this->customer);
    }

    #[On('attribute-selected')]
    public function addAttributeToCustomer(Attribute $attribute, mixed $value): void
    {
        app(AttributeAssignableService::class)->assign(
            $this->customer,
            $attribute,
            $value
        );

        $this->customerAttributes = $this->customer->attributes;
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
}
