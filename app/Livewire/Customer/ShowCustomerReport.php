<?php

namespace App\Livewire\Customer;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Domain\Skill\Services\Chart\ChartFactory;
use App\Domain\Skill\Services\FieldSkillChartService;
use App\Domain\Skill\Services\SoftSkillChartService;
use App\Models\Customer;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowCustomerReport extends Component
{
    public ?Customer $customer = null;
    public ?float $softSkillsAverage = null;
    public ?Collection $softSkills = null;
    public string $chartType = 'bar';
    public ?array  $chartDataSoft = [];
    public ?array  $chartDataField = [];

    public function mount(): void
    {
        $this->customer = Customer::findCustomerWithReview($this->customer->id);
        $this->getSoftSkill($this->customer);
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

    private function getSoftSkill(Customer $customer): void
    {
        $service = app(SoftSkillChartService::class);

        $this->softSkills = $service->buildSoftSkills($customer);
        $this->softSkillsAverage = $service->overallAverage($this->softSkills);
    }
}
