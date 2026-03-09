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
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
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
