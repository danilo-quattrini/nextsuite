<?php

namespace App\Livewire\Customer;

use App\Domain\Attribute\Services\AttributeAssignableService;
use App\Models\Customer;
use App\Models\Attribute;
use App\Domain\Skill\Services\SkillAssignmentService;
use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

    public function mount(Customer $customer): void
    {

        $this->customer = $customer->load('skills.category.fields');
        $this->customerAttributes = $this->customer->attributes;

        $this->customerSkills = $this->customer->skills;

        $this->fields = $this->customerSkills
            ->flatMap(fn ($skills) => $skills->category?->fields ?? collect())
            ->unique('id')
            ->values();

        $this->getSoftSkills($customer);
    }

    #[On('skill-selected')]
    public function addSkillToCustomer(int $skillId, int $skillLevel, int $skillYears): void
    {
        app(SkillAssignmentService::class)->assign(
            $this->customer,
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
        $chartId = 'softSkill' . Str::studly(Str::slug($categoryName, '_'));

        return Chartjs::build()
            ->name($chartId)
            ->type("polarArea")
            ->size(["width" => 150, "height" => 150])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Level",
                    "data" => $data,
                ],
            ])
            ->options([
                'responsive' => true,
                'scales' => [
                    'r' => [
                        'min' => 0,
                        'max' => 100,
                        'ticks' => [
                            'stepSize' => 10,
                        ],
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom'
                    ]
                ]

            ]);
    }

    public function getSoftSkills(Customer $customer): void
    {
        $skillsByCategory = $customer
            ->load('skills.category')
            ->skills
            ->filter(fn ($skill) => $skill->category?->type?->value === 'soft_skill')
            ->groupBy(fn ($skill) => $skill->category?->name ?? 'Uncategorized')
            ->map(function ($skills) {
                $skillsList = $skills->map(function ($skill) {
                    return [
                        'name' => $skill->name,
                        'level' => $skill->pivot?->level,
                    ];
                })->values();
                $average = $skillsList
                    ->pluck('level')
                    ->filter(fn ($level) => is_numeric($level))
                    ->avg();

                return [
                    'skills' => $skillsList,
                    'average' => $average,
                ];
            })
            ->sortKeys();

        $this->softSkills = $skillsByCategory;

        $allLevels = $skillsByCategory
            ->flatMap(fn ($group) => collect($group['skills'])->pluck('level'))
            ->filter(fn ($level) => is_numeric($level));

        $this->softSkillsAverage = $allLevels->isEmpty() ? null : $allLevels->avg();
    }

    public function render()
    {
        return view('livewire.customer.show-customer');
    }
}
