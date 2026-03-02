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

    #[On('customer-filters-updated')]
    public function applySkillFilters(array $skillIds): void
    {
        $this->selectedSkillIds = $skillIds;
        $this->resetPage();
    }

    public function render()
    {
        $customers = empty($this->selectedSkillIds)
            ? Customer::getCustomersWithReviews()
            : Skill::findCustomerWithSkills($this->selectedSkillIds);

        return view('livewire.customer.customer-table', [
            'customers' => $customers
        ]);
    }
}
