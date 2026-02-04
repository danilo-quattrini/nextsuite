<x-app-layout>
    <div class="max-w-6xl mx-auto space-y-8">

        {{-- Page header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-black">
                    Company overview
                </h1>
                <p class="text-sm text-primary-grey mt-1">
                    General information and structure of your organization
                </p>
            </div>
        </div>

        {{-- NO COMPANY --}}
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

            {{-- COMPANY INFO --}}
            <div class="bg-white rounded-xl border border-outline-grey divide-y">

                {{-- Header --}}
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <div class="p-6 flex items-start gap-6">
                            <x-profile-image
                                :src="$company->business_photo"
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
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div class="rounded-md border border-outline-grey p-5">
                            <p class="text-sm text-primary-grey">
                                Employees
                            </p>
                            <p class="text-2xl font-semibold text-black mt-1">
                                {{ $company->employees }}
                            </p>
                        </div>

                        <div class="md:col-span-2 rounded-md border border-outline-grey p-5">
                            <p class="text-sm text-primary-grey mb-3">
                                Business fields
                            </p>

                            <div class="flex flex-wrap gap-2">
                                @foreach($company->fields as $fields)
                                    <x-tag variant="white" size="auto">
                                        {{ $fields->name }}
                                    </x-tag>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</x-app-layout>
