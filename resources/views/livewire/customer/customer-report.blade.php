<div class="page-content__body">
    <div class="page-content__grid">
        @foreach($customers as $customer)
            <x-user-card :user="$customer" />
        @endforeach
    </div>

    <div class="page-content__pagination">
        {{ $customers->links('components.pagination') }}
    </div>
</div>