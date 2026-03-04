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
                        <p class="page-content__stat-value">{{ $customers->total() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content__body">

        <div class="flex items-center justify-start gap-4">
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
            <div class="table-container">
                <table class="min-w-full w-full leading-normal">
                    <thead>
                    <tr>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="user" size="sm"/>
                                <span>User</span>
                            </div>
                        </th>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="envelope" size="sm"/>
                                <span>Email</span>
                            </div>
                        </th>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="phone" size="sm"/>
                                <span>Phone</span>
                            </div>
                        </th>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="calendar-days" size="sm"/>
                                <span>DOB</span>
                            </div>
                        </th>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="user-circle" size="sm"/>
                                <span>Gender</span>
                            </div>
                        </th>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="globe-europe-africa" size="sm"/>
                                <span>Nationality</span>
                            </div>
                        </th>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="star" size="sm"/>
                                <span>Reviews</span>
                            </div>
                        </th>
                        <th
                                class="table-head__container">
                            <div class="table-head__content">
                                <x-heroicon name="ellipsis-horizontal" size="sm"/>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-grey">
                    @foreach($customers as $customer)
                        <tr
                                class="bg-white hover:bg-outline-grey/60 transition"
                        >
                            <td class="p-6 bg-white">
                                <div class="flex items-center gap-6">
                                    <x-profile-image
                                            :src="$customer->profile_photo_url"
                                            :name="$customer->full_name"
                                            directory="customers-profile-photos"
                                            size="custom"
                                            class="w-10 h-10"
                                    />
                                    <p class="table-text">
                                        {{ $customer->full_name }}
                                    </p>
                                </div>
                            </td>
                            <td class="p-6 bg-white">
                                <p class="table-text">{{ $customer->email}}</p>
                            </td>
                            <td class="p-6 bg-white hidden md:table-cell">
                                <p class="table-text">{{ $customer->phone}}</p>
                            </td>
                            <td class="p-6 bg-white hidden md:table-cell">
                                <p class="table-text">{{ date_format($customer->dob, 'd-m-Y')}}</p>
                            </td>
                            <td class="p-6 bg-white hidden md:table-cell">
                                <p class="table-text">{{ $customer->gender  }}</p>
                            </td>
                            <td class="p-6 bg-white hidden md:table-cell">
                                <p class="table-text">{{ $customer->nationality  }}</p>
                            </td>
                            <td class="p-6 bg-white hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <x-heroicon
                                            variant="solid"
                                            name="star"
                                            class="text-secondary-warning"
                                    />
                                    <span class="table-text">
                                        {{ number_format($customer->reviews_avg_rating ?? 0, 1) }}
                                        ({{ $customer->reviews_count }})
                                    </span>
                                </div>
                            </td>
                            <td
                                    class="relative p-6 bg-white hidden md:table-cell"
                            >
                                <x-form.dropdown-button align="right">
                                    <x-slot:trigger>
                                        <x-button
                                                type="button"
                                                variant="white"
                                                size="auto"
                                                aria-label="Customer actions"
                                        >
                                            <x-heroicon
                                                    name="ellipsis-vertical"
                                            />
                                        </x-button>
                                    </x-slot:trigger>

                                    <x-slot:content>
                                        <div class="flex-col items-center space-y-3">
                                            <div class="flex flex-col space-y-2 min-w-40">
                                                <a
                                                        href="{{ route('customer.show', $customer) }}"
                                                        class="flex items-center gap-2 px-3 py-2 rounded-md text-sm hover:bg-secondary transition"
                                                >
                                                    <x-heroicon name="information-circle" class="text-primary" />
                                                    <span>View Customer</span>
                                                </a>

                                                <a
                                                        href="{{ route('customer.edit', $customer) }}"
                                                        class="flex items-center gap-2 px-3 py-2 rounded-md text-sm hover:bg-outline-grey transition"
                                                >
                                                    <x-heroicon name="pencil-square" class="text-primary-grey" />
                                                    <span>Edit customer</span>
                                                </a>

                                                <button
                                                        type="button"
                                                        wire:click.prevent="$dispatch('delete-element', { id: {{ $customer->id }}, type: 'customer' })"
                                                        class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-secondary-error hover:bg-secondary-error-100 cursor-pointer transition"
                                                >
                                                    <x-heroicon name="trash" />
                                                    <span>Delete</span>
                                                </button>

                                                <button
                                                        type="button"
                                                        wire:click.prevent="$dispatch('review-user', { id: {{ $customer->id }}, type: 'customer' })"
                                                        class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-secondary-warning hover:bg-secondary-warning-100 cursor-pointer transition"
                                                >
                                                    <x-heroicon name="star" />
                                                    <span>Review</span>
                                                </button>
                                            </div>
                                        </div>
                                    </x-slot:content>
                                </x-form.dropdown-button>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CARD VIEW --}}
        <div
                x-show="$store.views.current === 'card'"
                x-transition
                class="page-content__grid"
        >
            @foreach($customers as $customer)
                <x-user-card :user="$customer" href="customer.show" />
            @endforeach
        </div>

        @if(count($customers) < 7)
            <div class="page-content__pagination">
                {{ $customers->links('components.pagination') }}
            </div>
        @endif
    </div>

    <x-popup.delete-popup :show-delete-modal="$showDeleteModal"/>
    <x-popup.review-popup :show-modal="$showModal" :rating="$rating"/>
</div>
