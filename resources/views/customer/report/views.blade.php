<x-app-layout>
    <div class="mx-auto px-4 sm:px-8">

        <div class="flex-col space-y-4 mb-4">
            <div>
                <h1>
                    {{ __('Reports') }}
                </h1>

                <p class=" text-primary-grey">
                    {{__('Select a customer where you can see its report.')}}
                </p>
            </div>
        </div>
        @livewire('customer.customer-report')
    </div>
</x-app-layout>
