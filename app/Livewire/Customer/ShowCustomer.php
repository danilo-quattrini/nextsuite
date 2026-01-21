<?php

namespace App\Livewire\Customer;


use App\Models\Customer;
use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowCustomer extends Component
{
    public Customer $customer;
    public Collection $customerAttributes;
    public Collection $customerSkills;
    public Collection $fields;
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
                        'max' => 5,
                        'ticks' => [
                            'stepSize' => 1
                        ]
                    ]
                ]
            ]);
    }
    public function getData()
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
