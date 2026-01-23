<?php

namespace App\Livewire\Customer;

use App\Domain\Attribute\Services\AttributeAssignableService;
use App\Models\Customer;
use App\Models\Attribute;
use App\Domain\Skill\Services\SkillAssignmentService;
use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowCustomer extends Component
{
    public Customer $customer;

    public ?Collection $customerAttributes = null;
    public ?Collection $customerSkills = null;
    public ?Collection $fields = null;
    public $datasets;

    public function mount(Customer $customer): void
    {

        $this->customer = $customer->load('skills.category.fields');
        $this->customerAttributes = $this->customer->attributes;

        $this->customerSkills = $this->customer->skills;

        $this->fields = $this->customerSkills
            ->flatMap(fn ($skills) => $skills->category?->fields ?? collect())
            ->unique('id')
            ->values();

        $this->getData();
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

        $this->getData();
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
    #[Computed]
    public function chart(): Builder
    {
        return Chartjs::build()
            ->name("Soft-Skill-Diagram")
            ->livewire()
            ->model("datasets")
            ->type("polarArea")
            ->size(["width" => 600, "height" => 500])
            ->options([
                'scales' => [
                    'r' => [
                        'min' => 0,
                        'max' => 100,
                        'ticks' => [
                            'stepSize' => 10
                        ]
                    ]
                ]
            ]);
    }
    public function getData(): void
    {
        $data = []; // your data here
        $labels = [];
        foreach ($this->customerSkills as $skill) {
            if($skill->category->name == 'Abilities') {
                $labels[] = $skill->name;
            }
        }
        foreach ($this->customerSkills as $skill){
            if($skill->category->name == 'Abilities') {
                $data[] = $skill->pivot->level;
            }
        }

        $this->datasets = [
            'datasets' => [
                [
                    "label" => "Level",
                    "data" => $data

                ]
            ],
            'labels' => $labels
        ];
    }
    public function render()
    {
        return view('livewire.customer.show-customer');
    }
}
