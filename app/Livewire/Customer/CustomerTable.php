<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Traits\DeleteModal;
use App\Traits\WithReview;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;
    use WithReview;
    use DeleteModal;

    protected string $paginationTheme = 'tailwind';

    public array $selectedSkillIds = [];
    public ?int $selectedRatingStars = 0;
    public ?string $roleToSearch = '';

    #[Computed]
    public function tableColumns(): array
    {
        return [
            [
                'key' => 'full_name',
                'label' => 'User',
                'icon' => 'user',
                'visible' => true,
                'hiddenOnMobile' => false,
            ],
            [
                'key' => 'email',
                'label' => 'Email',
                'icon' => 'envelope',
                'visible' => true,
                'hiddenOnMobile' => false,

            ],
            [
                'key' => 'phone',
                'label' => 'Phone',
                'icon' => 'phone',
                'visible' => true,
                'hiddenOnMobile' => true,
            ],
            [
                'key' => 'dob',
                'label' => 'DOB',
                'icon' => 'calendar-days',
                'type' => 'date',
                'visible' => true,
                'hiddenOnMobile' => true,
            ],
            [
                'key' => 'gender',
                'label' => 'Gender',
                'icon' => 'user-circle',
                'visible' => true,
                'hiddenOnMobile' => true,
            ],
            [
                'key' => 'nationality',
                'label' => 'Nationality',
                'icon' => 'globe-europe-africa',
                'visible' => true,
                'hiddenOnMobile' => true,
            ],
            [
                'key' => 'reviews_avg_rating',
                'label' => 'Reviews',
                'icon' => 'star',
                'visible' => true,
                'hiddenOnMobile' => true,
                'format' => function($value, $row) {
                    $rating = number_format($value ?? 0, 1);
                    $count = $row->reviews_count ?? 0;
                    return '<div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary-warning fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span>' . $rating . ' (' . $count . ')</span>
                    </div>';
                }
            ]
        ];
    }
    #[Computed]
    public function tableActions(): array
    {
        return [
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
        ];
    }

    #[On('customer-filters-updated')]
    public function applyFilters(
        ?string $roleToSearch,
        ?array $skillIds = [],
        ?int $ratingStars = 0
    ): void
    {
        $this->roleToSearch = $roleToSearch;
        $this->selectedSkillIds = $skillIds;
        $this->selectedRatingStars = $ratingStars;

        $this->resetPage();
    }

    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        return Customer::findCustomerWithSkillsAndReviews(
            $this->roleToSearch,
            $this->selectedSkillIds,
            $this->selectedRatingStars
        );
    }

    public function render()
    {
        return view('livewire.customer.customer-table');
    }
}
