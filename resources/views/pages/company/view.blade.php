<?php

use App\Models\Company;
use Livewire\Component;

new class extends Component {

    public ?Company $company = null;

    public bool $isOwner = false;

    /**
     * Load the user and then the company where
     * the user belongs to or has, and trigger a
     * flag if the user's the owner of it or not.
     * */
    public function mount(): void
    {
        $user = Auth::user();

        $this->company = $user->company ?? $user->companies->first();
        $this->isOwner = $user->company !== null;
    }
};
?>
<x-card.content-page-card
        title="Company"
        description="Manage your company details."
>
    @if (!$company)
        {{--    CARD IF NO COMPANY HAS BEEN LOADED    --}}
        <div class="rounded-xl border border-dashed border-outline-grey p-10 text-center bg-white">
            <x-heroicon name="building-office" class="mx-auto h-12 w-12 text-primary-grey" />
            <h2 class="mt-4 text-lg font-semibold text-black">No company associated</h2>
            <p class="mt-2 text-sm text-primary-grey">
                You currently don't belong to any company. Please contact an administrator or create one.
            </p>
            <div class="flex justify-center items-center mt-6 gap-sm">
                <x-button
                        href=""
                        size="large"
                        variant="rest"
                >
                    Join
                </x-button>

                <p> or </p>
                <x-button
                        href="{{ route('company.create') }}"
                        size="large"
                >
                    Create company
                </x-button>
            </div>
        </div>
    @else
        <x-card.card-container>
            {{-- Header: logo + info + delete --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 p-6">
                <div class="flex items-start gap-md">

                    {{-- COMPANY LOGO --}}
                    <x-profile-image
                            :src="$company->company_photo"
                            :name="$company->name"
                            directory="business-profile-photos"
                            size="small"
                            alt="Company logo"
                    />

                    {{-- COMPANY DETAILS --}}
                    <div class="space-y-xs">
                        <h2>{{ $company->name }}</h2>
                        <p class="text-sm text-primary-grey">Company details</p>
                        <div class="flex flex-wrap gap-sm text-sm text-black pt-xs">
                            <div class="flex items-center gap-xs">
                                <x-heroicon name="phone" class="text-primary-grey"/>
                                <span>{{ $company->phone }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-heroicon name="identification" class="text-primary-grey"/>
                                <span>{{ $company->vat_number }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <hr class="border-outline-grey mx-6" />

            {{-- COMPANY STATS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-6">

                {{-- NUMBERS OF EMPLOYEE --}}
                <div class="rounded-md border border-outline-grey p-md">
                    <p class="text-primary-grey">Employees</p>
                    <h3 class="py-sm">
                        {{ $company->employee->count() ?: '—' }}
                    </h3>
                </div>

                {{-- FIELDS THE COMPANY BELONG TO --}}
                <div class="sm:col-span-2 rounded-md border border-outline-grey p-md">
                    <p class="text-primary-grey">Business fields</p>
                    <div class="flex flex-wrap gap-sm py-sm">
                        @forelse($company->fields as $field)
                            <x-tag variant="white">
                                {{ $field->name }}
                            </x-tag>
                        @empty
                            <span class="text-primary-grey">No fields assigned</span>
                        @endforelse
                    </div>
                </div>

            </div>
        </x-card.card-container>
    @endif
</x-card.content-page-card>