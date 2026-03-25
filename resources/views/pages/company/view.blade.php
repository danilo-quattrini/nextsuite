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
        description="Welcome these are all the company you got."
>
    @if (! $company)
        <div class="rounded-xl border border-dashed border-gray-300 p-10 text-center bg-white">
            <x-heroicon name="building-office" class="mx-auto h-12 w-12 text-primary-grey" />

            <h2 class="mt-4 text-lg font-semibold text-black">
                No company associated
            </h2>

            <p class="mt-2 text-sm text-primary-grey">
                You currently don’t belong to any company.
                Please contact an administrator or create one.
            </p>

            <div class="flex justify-center mt-6">
                <x-button href="{{ route('company.create') }}" size="large">
                    Create company
                </x-button>
            </div>
        </div>
    @else
        <x-card.card-container>
            <div class="p-6 flex items-center justify-between">
                <div>
                    <div class="p-6 flex items-start gap-6">
                        <x-profile-image
                                :src="$company->company_photo"
                                :name="$company->name"
                                directory="business-profile-photos"
                                size="small"
                                alt="Company logo"
                        />

                        <div class="flex-1 space-y-2">
                            <h2 class="text-xl font-semibold text-black">
                                {{ $company->name }}
                            </h2>

                            <p class="text-sm text-primary-grey">
                                Company details
                            </p>

                            <div class="flex flex-wrap gap-6 text-sm text-black mt-3">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="phone" class="h-4 w-4 text-primary-grey"/>
                                    <span>{{ $company->phone }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="identification" class="h-4 w-4 text-primary-grey"/>
                                    <span>{{ $company->vat_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-md grid grid-cols-1 md:grid-cols-3 gap-md">

                    <div class="rounded-md border border-outline-grey p-md">
                        <p class="text-sm text-primary-grey">
                            Employees
                        </p>
                        <p class="text-3xl font-semibold text-black my-sm">
                            {{ count($company->employee) > 0 ?: 'N.a.N' }}
                        </p>
                    </div>

                    <div class="md:col-span-2 rounded-md border border-outline-grey p-md">
                        <p class="text-sm text-primary-grey">
                            Business fields
                        </p>

                        <div class="flex flex-wrap gap-sm py-sm">
                            @foreach($company->fields as $fields)
                                <x-tag variant="white">
                                    {{ $fields->name }}
                                </x-tag>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </x-card.card-container>
        {{-- COMPANY INFO --}}

    @endif
</x-card.content-page-card>