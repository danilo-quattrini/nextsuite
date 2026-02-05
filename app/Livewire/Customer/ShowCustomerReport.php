<?php

namespace App\Livewire\Customer;

use App\Domain\Skill\Services\Chart\ChartFactory;
use App\Domain\Skill\Services\FieldSkillChartService;
use App\Domain\Skill\Services\SoftSkillChartService;
use App\Models\Customer;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowCustomerReport extends Component
{
    public Customer $customer;
    public ?float $softSkillsAverage = null;
    public ?Collection $softSkills = null;
    public ?float $fieldSkillsAverage = null;
    public ?Collection $fieldSkills = null;
    public string $chartType = 'bar';
    public string $fieldChartType = 'pie';
    public ?array  $chartDataSoft = [];
    public ?array  $chartDataField = [];

    public function mount(Customer $customer): void
    {
        $this->customer = $customer
            ->load('skills.category.fields')
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating');

        $this->getSoftSkill($customer);
        $this->getFieldSkills($customer);
    }

    public function render()
    {
        return view('livewire.customer.show-customer-report');
    }

    public function buildSoftSkillChart(string $categoryName, array $labels, array $data): Builder
    {
        $chart = ChartFactory::make($this->chartType);
        $this->chartDataSoft = $chart->buildDataset($labels, $data);

        return $chart->buildChart($categoryName, $labels, $data)
            ->livewire()
            ->model("chartDataSoft");
    }

    public function buildFieldSkillChart(string $chartName, array $labels, array $data): Builder
    {
        $chart = ChartFactory::make($this->fieldChartType);
        $this->chartDataField = $chart->buildDataset($labels, $data);

        return $chart->buildChart($chartName, $labels, $data)
            ->livewire()
            ->model("chartDataField");
    }

    private function getSoftSkill(Customer $customer): void
    {
        $service = app(SoftSkillChartService::class);

        $this->softSkills = $service->buildSoftSkills($customer);
        $this->softSkillsAverage = $service->overallAverage($this->softSkills);
    }

    private function getFieldSkills(Customer $customer): void
    {
        $service = app(FieldSkillChartService::class);

        $this->fieldSkills = $service->buildFieldSkills($customer);
        $this->fieldSkillsAverage = $service->overallAverage($this->fieldSkills);
    }
}
