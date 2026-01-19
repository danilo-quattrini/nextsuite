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
            ->name("ExampleDiagram")
            ->livewire()
            ->model("datasets")
            ->type("pie");
    }
    public function getData()
    {
        $data = []; // your data here
        $labels = [];
        foreach ($this->fields as $field) {
            $labels[] = $field->name;
        }
        foreach ($this->customerSkills as $skill){
            $data[] = $skill->pivot->level;
        }

        $this->datasets = [
            'datasets' => [
                [
                    'backgroundColor' => ['#5E81F4', '#5E81F440'],
                    "label" => "Skill Level",
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
