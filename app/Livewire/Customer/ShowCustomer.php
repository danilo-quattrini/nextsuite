<?php

namespace App\Livewire\Customer;

use App\Domain\Attribute\Services\AttributeAssignableService;
use App\Models\Customer;
use App\Models\Attribute;
use App\Domain\Skill\Services\SkillAssignmentService;
use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Services\Charts\Skill\SoftSkillChartService;

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

        $this->customerSkills = $this->customer->skills;

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
        $chartId = 'softSkill' . Str::studly(Str::slug($categoryName, '_'));
        $barColors = $this->mapChartColors($data);
        $dataset = [
            'labels' => $labels,
            'datasets' => [
                [
                    "barThickness" => 30,
                    "minBarLength" => 0,
                    "label" => "Level",
                    "data" => $data,
                    "backgroundColor" => $barColors,
                    "borderColor" => $barColors,
                    "categoryPercentage" => 0.9,
                    "barPercentage" => 1.0,
                ],
            ],
        ];
        $this->softSkillChartData = $dataset;

        return Chartjs::build()
            ->name($chartId)
            ->type("bar")
            ->size(["width" => 500, "height" => 500])
            ->labels($labels)
            ->datasets($dataset['datasets'])
            ->livewire()
            ->model('softSkillChartData')
            ->options($this->softSkillChartOptions());
    }

    private function mapChartColors(array $data): array
    {
        $palette = [
            '#5E81F450',
            'rgba(255, 75, 90, 0.50)',
            'rgba(244, 190, 94, 0.50)',
            'rgba(124, 231, 172, 0.50)',
            '#8181a5',
            '#4c5fae',
        ];

        return collect($data)
            ->values()
            ->map(fn ($_, $index) => $palette[$index % count($palette)])
            ->all();
    }

    private function softSkillChartOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'responsive' => true,
            'layout' => [
                'padding' => [
                    'top' => 15,
                    'bottom' => 15,
                ],
            ],
            'elements' => [
                'bar' => [
                    'borderWidth' => 0.5,
                ],
            ],
            'scales' => [
                'y' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'stepSize' => 1,
                        'display' => false,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'display' => false,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
            ],
            'plugins' => [
                'softSkillBarLabels' => [
                    'labelColor' => '#8181a5',
                    'valueColor' => '#1c1d21',
                    'fontSize' => 16,
                    'valueFontSize' => 24,
                ],
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
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
