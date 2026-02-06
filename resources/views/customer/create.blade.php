<x-app-layout>
    <div class="page-content__container">

        {{--  Content  --}}
        <div class="page-content__hero">
            <div class="page-content__hero-inner">
                <div class="page-content__hero-row">
                    <div class="page-content__hero-copy">
                        <h2 class="page-content__title">
                            {{ __('Create Customer') }}
                        </h2>
                        <p class="page-content__subtitle">
                            {{__('Complete the details in two steps.')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content__body">
            {{--  Form  --}}
            <div class="page-content__card">
                <x-form.container>
                    @livewire('customer.create-customer')
                </x-form.container>
            </div>
        </div>

    </div>
</x-app-layout>
