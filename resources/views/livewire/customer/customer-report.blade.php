<div class="page-content__container">
    <div class="page-content__hero">
        <div class="page-content__hero-inner">
            <div class="page-content__hero-row">
                <div class="page-content__hero-copy">
                    <h2 class="page-content__title">
                        {{ __('Reports') }}
                    </h2>
                    <p class="page-content__subtitle">
                        {{__('Select a customer that you want to see its report')}}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content__body">
        <div class="page-content__grid">
            @foreach($customers as $customer)
                <x-user-card :user="$customer" href="report.show"/>
            @endforeach
        </div>

        <div class="page-content__pagination">
            {{ $customers->links('components.pagination') }}
        </div>
    </div>
</div>