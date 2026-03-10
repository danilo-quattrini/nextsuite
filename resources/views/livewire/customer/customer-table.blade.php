<div
        x-data="tableView('table')"
        class="page-content__container"
>
    <div class="page-content__hero">
        <div class="page-content__hero-inner">
            <div class="page-content__hero-row">
                <div class="page-content__hero-copy">
                    <h2 class="page-content__title">
                        {{ __('Customer List') }}
                    </h2>
                    <p class="page-content__subtitle">
                        {{__('These are all the customers you got.')}}
                    </p>
                </div>
                <div class="page-content__stats">
                    <div class="page-content__stat-card">
                        <p class="page-content__stat-label">Customers</p>
                        <p class="page-content__stat-value">{{ $this->customers->total() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content__body">

        <div class="flex items-center justify-start gap-2">
            {{-- SWITCH --}}
            <div class="page-content__switch">
                <button
                        @click="$store.views.set('table')"
                        :class="$store.views.current === 'table' ? 'bg-primary text-white' : ''"
                        class="page-content__switch-buttons"
                >
                    <x-heroicon name="list-bullet"/>
                </button>
                <button
                        @click="$store.views.set('card')"
                        :class="$store.views.current === 'card' ? 'bg-primary text-white' : ''"
                        class="page-content__switch-buttons"
                >
                    <x-heroicon name="squares-2x2"/>
                </button>
            </div>

            {{-- FILTERS --}}
            <x-button
                    size="auto"
                    wire:click="dispatch('open-section')"
            >
                <x-heroicon name="funnel" size="md"/>
                Filter
            </x-button>
        </div>

        {{-- FILTER SECTION --}}
        <livewire:filters.filter-section />

        {{-- TABLE VIEW --}}
        <div
                x-show="$store.views.current === 'table'"
                class="table-view"
                x-transition
        >
            <x-data-table
                    :table-data="$this->customers"
                    :columns="$this->tableColumns"
                    :actions="$this->tableActions"
                    resource-type="customer"
                    photo-field="profile_photo_url"
                    name-field="full_name"
            />
        </div>

        {{-- CARD VIEW --}}
        <div
                x-show="$store.views.current === 'card'"
                x-transition
                class="page-content__grid"
        >
            @foreach($this->customers as $customer)
                <x-user-card :user="$customer" href="customer.show" />
            @endforeach
        </div>

        @if(count($this->customers) === 0)
            <x-card.card-container
                    size="lg"
            >
                <x-empty-state
                        icon="user"
                        message="No customer available"
                        description="sorry but at this moment there is no customer here"
                />
            </x-card.card-container>
        @endif
        @if(count($this->customers) < 7 && count($this->customers) !== 0)
            <div class="page-content__pagination">
                {{ $this->customers->links('components.pagination') }}
            </div>
        @endif
    </div>

    <x-popup.delete-popup :show-delete-modal="$showDeleteModal"/>
    <x-popup.review-popup :show-modal="$showModal" :rating="$rating"/>
</div>
