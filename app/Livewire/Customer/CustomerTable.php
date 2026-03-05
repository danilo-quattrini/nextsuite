<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Models\Skill;
use App\Traits\DeleteModal;
use App\Traits\WithReview;
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

    public function render()
    {
        $customers = Customer::findCustomerWithSkillsAndReviews(
            $this->roleToSearch,
            $this->selectedSkillIds,
            $this->selectedRatingStars
        );
        return view('livewire.customer.customer-table', [
            'customers' => $customers
        ]);
    }
}
