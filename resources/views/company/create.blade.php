<x-app-layout>
    <div class="page-content__container">

        {{--  Content  --}}
        <div class="page-content__hero">
            <div class="page-content__hero-inner">
                <div class="page-content__hero-row">
                    <div class="page-content__hero-copy">
                        <h2 class="page-content__title">
                            {{ __('Create Company') }}
                        </h2>
                        <p class="page-content__subtitle">
                            {{__('Create your first company in a few step!')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content__body">
            <div class="page-content__card">
                {{--  Form  --}}
                @livewire('create-company')
            </div>
        </div>
    </div>
</x-app-layout>
