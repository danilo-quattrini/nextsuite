<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Traits\DeleteModal;
use App\Traits\WithReview;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
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
    public bool $showFilterSection = false;

    /**
     * Authorize only the users who are able to see the
     * customers and have the permission customer.read
     * */
    public function mount(): void
    {
        $this->authorize('view-any', Customer::class);
    }

    public function render(): View
    {
        return view('livewire.customer.customer-table');
    }

    /**
     * Method that maps each column of the table we are going to create with this format.
     * ```
     *   [
     *      'key' => 'full_name',
     *      'label' => 'User',
     *      'icon' => 'user',
     *      'visible' => true,
     *      'hiddenOnMobile' => false,
     *   ]
     * ```
     * @return array for the table
     * */
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
                'key' => 'roles',
                'label' => 'Roles',
                'icon' => 'user-circle',
                'visible' => true,
                'hiddenOnMobile' => false,
                'format' => function ($roles) {
                    foreach ($roles as $role){
                        return '<span>' . ucfirst($role->name) . ' </span>';
                    }
                    return '---';
                }
            ],
            [
                'key' => 'reviews_avg_rating',
                'label' => 'Reviews',
                'icon' => 'star',
                'visible' => true,
                'hiddenOnMobile' => true,
                'format' => function($value) {
                    $rating = number_format($value ?? 0, 1);
                    return '<div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-secondary-warning fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span>' . $rating . ' </span>
                    </div>';
                }
            ]
        ];
    }

    /**
     * Method that set an array of actions to perform into the table.
     * ```
     *   [
     *      'label' => 'label name to show',
     *      'icon' => name of the icon,
     *      'route' => web.php route to perform in,
     *      'color' => button color,
     *   ]
     * ```
     * @return array for the action section in the data-table
     * */
    #[Computed]
    public function tableActions(): array
    {
        return [
            [
                'label' => 'View Customer',
                'icon' => 'information-circle',
                'route' => 'customer.show',
                'color' => 'primary',
            ],
            [
                'label' => 'Edit customer',
                'icon' => 'pencil-square',
                'route' => 'customer.edit',
                'color' => 'default',
            ],
            [
                'label' => 'Delete',
                'icon' => 'trash',
                'event' => 'delete-element',
                'color' => 'danger',
            ],
            [
                'label' => 'Review',
                'icon' => 'star',
                'event' => 'review-user',
                'color' => 'warning',
            ]
        ];
    }

    /**
     * Open the filter dropdown section to show in the view.
     * */
    public function openFilterSection(): void
    {
        $this->dispatch('toggle-filter-section');
    }

    /**
     * Method that receives an event of type 'customer-filters-updated',
     * where it will take the @param  string|null  $roleToSearch role to search
     * in the database, the @param  array|null  $skillIds skill ids' to search on the
     * customer and in the end the @param  int|null  $ratingStars to see.
     *
     * It will assign them to the class variables and reset the pagination to the
     * first page.
     * */
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
}
