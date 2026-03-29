<?php

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    #[Computed(cache: false)]
    public function companies(): LengthAwarePaginator
    {
        return Company::getCompanies(page: $this->getPage());
    }

};
?>

<x-card.content-page-card
        title="Companies"
        description="Select a company where you want to join"
        :has-counter="true"
        :has-grid="true"
        :counter-title="Str::plural('Company', count($this->companies) ?? 0)"
        :counter-value="$this->companies->total()"
>


</x-card.content-page-card>