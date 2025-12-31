<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Page header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Company
            </h1>
            <p class="text-sm text-gray-500">
                Manage and view your company information
            </p>
        </div>

        {{-- NO COMPANY --}}
        @if (! $company)
            <div class="rounded-xl border border-dashed border-gray-300 p-10 text-center bg-white">
                <x-heroicon name="building-office" class="mx-auto h-12 w-12 text-gray-400" />

                <h2 class="mt-4 text-lg font-semibold text-gray-900">
                    No company associated
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    You currently don’t belong to any company.
                    Please contact an administrator or create one.
                </p>

                <div class="flex justify-center mt-6">
                    <x-button href="#" size="large">
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
                        <div>
                            <img class=" border border-primary-grey rounded-full h-24 w-24" src="{{ asset('storage/business-profile-photos/' . $company->business_photo) }}" alt="company profile photo"/>
                        </div>
                        <h2 class="text-xl font-semibold">
                            {{ $company->name }}
                        </h2>
                        <p class="text-sm text-primary-grey">
                            Company details
                        </p>
                    </div>
                </div>
            </div>

        @endif
    </div>
</x-app-layout>