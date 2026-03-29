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

    @forelse ($this->companies as $company)
        <x-company-card :company="$company" :showFields="true">

            {{-- ── Join button injected via slot ──────────── --}}
            <x-button
                    size="full"
                    wire:click="$dispatch('request-join', { companyId: {{ $company->id }} })"
            >
                Request to join
            </x-button>

        </x-company-card>
    @empty
        <x-card.card-container
                class="col-span-3"
                card-size="lg"
        >
            <x-empty-state
                    icon="building-office"
                    message="No company has been created or founded"
                    description="Try again or create a company by your own"
            >
                <x-slot:action>
                    <x-button
                            size="full"
                            variant="white"
                            href="{{ route('dashboard') }}"
                    >
                        <x-heroicon name="home"/>
                        Home
                    </x-button>
                    <p> or </p>
                    <x-button
                            size="full"
                            href="{{route('company.create')}}"
                    >
                        <x-heroicon name="plus"/>
                        Create Company
                    </x-button>
                </x-slot:action>
            </x-empty-state>
        </x-card.card-container>
    @endforelse

        {{-- ── Pagination ───────────────────────────────────────── --}}
        @if ($this->companies->hasPages())
            <x-slot:pagination>
                <div class="page-content__pagination">
                    {{ $this->companies->links('components.pagination', data: ['scrollTo' => false]) }}
                </div>
            </x-slot:pagination>
        @endif
</x-card.content-page-card>