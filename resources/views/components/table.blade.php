@props(['customers' => null])
<div x-data="tableView('table')">
    <div class="mx-auto px-4 sm:px-8">

        <div class="flex-col space-y-4 mb-4">
            <div>
                <h1>
                    {{ __('Customer List') }}
                </h1>

                <p class=" text-primary-grey">
                    {{__('These are all the customers you got.')}}
                </p>
            </div>
        </div>

        <div class="flex justify-between items-center gap-2 p-1 border-none bg-outline-grey rounded-md max-w-fit">
            <button
                    @click="$store.views.set('table')"
                    :class="$store.views.current === 'table' ? 'bg-white' : ''"
                    class="p-2 rounded-md"
            >
                <x-heroicon name="list-bullet" size="lg"/>
            </button>
            <button
                    @click="$store.views.set('card')"
                    :class="$store.views.current === 'card' ? 'bg-white' : ''"
                    class="p-2 rounded-md"
            >
                <x-heroicon name="squares-2x2" size="lg"/>
            </button>
        </div>

        {{-- TABLE VIEW --}}
        <div
                x-show="$store.views.current === 'table'"
                class="relative mx-4 sm:-mx-8 px-4 sm:px-8 py-4"
                x-transition
        >
            <div class="inline-block min-w-full align-middle shadow overflow-visible">
                <table class="min-w-full w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                    class="table-head">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="user" size="sm" class="text-black" />
                                    <span>User</span>
                                </div>
                            </th>
                            <th
                                    class="table-head">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="envelope" size="sm" class="text-black" />
                                    <span>Email</span>
                                </div>
                            </th>
                            <th
                                    class="table-head hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="phone" size="sm" class="text-black" />
                                    <span>Phone</span>
                                </div>
                            </th>
                            <th
                                    class="table-head hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="calendar-days" size="sm" class="text-black" />
                                    <span>DOB</span>
                                </div>
                            </th>
                            <th
                                    class="table-head hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="user-circle" size="sm" class="text-black" />
                                    <span>Gender</span>
                                </div>
                            </th>
                            <th
                                    class="table-head hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="globe-europe-africa" size="sm" class="text-black" />
                                    <span>Nationality</span>
                                </div>
                            </th>
                            <th
                                    class="table-head hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="star" size="sm" class="text-black" />
                                    <span>Reviews</span>
                                </div>
                            </th>
                            <th
                                    class="table-head">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="ellipsis-horizontal" size="sm" class="text-black" />
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
                                <td class="p-6 bg-white text-sm">
                                    <div class="flex items-center gap-6">
                                        <div class="shrink-0 w-10 h-10">
                                            <x-profile-image
                                                :src="$customer->profile_photo_url"
                                                :name="$customer->full_name"
                                                directory="customers-profile-photos"
                                                size="custom"
                                                class="w-10 h-10"
                                            />
                                        </div>
                                        <div>
                                            <p class="table-text">
                                                {{ $customer->full_name }}
                                            </p>
                                        </div>
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
                                                            href="#"
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
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6"
        >
            @foreach($customers as $customer)
                <div
                        class="bg-white rounded-md border border-outline-grey p-6 flex flex-col gap-4
                        cursor-pointer hover:shadow-md transition"
                        onclick="window.location='{{ route('customer.show', $customer) }}'"
                >
                    {{-- Header --}}
                        <div class="flex justify-between">
                            <div class="flex justify-center items-center gap-4">
                                <x-profile-image
                                    :src="$customer->profile_photo_url"
                                    :name="$customer->full_name"
                                    directory="customers-profile-photos"
                                    size="custom"
                                    class="w-12 h-12"
                                />

                            <div>
                                <p class="font-semibold">{{ $customer->full_name }}</p>
                                <p class="text-sm text-primary-grey">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <div
                                class="flex float-end"
                                onclick="event.stopPropagation()"
                        >
                            <x-form.dropdown-button align="right">
                                <x-slot:trigger>
                                    <x-button
                                            type="button"
                                            variant="white"
                                            size="auto"
                                            aria-label="Customer actions 2"
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
                                                    href="#"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-md text-sm hover:bg-outline-grey transition duration-150"
                                            >
                                                <x-heroicon name="pencil-square" class="text-primary-grey" />
                                                <span>Edit customer</span>
                                            </a>

                                            <button
                                                    type="button"
                                                    wire:click.prevent="$dispatch('delete-element', { id: {{ $customer->id }}, type: 'customer' })"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-secondary-error hover:bg-secondary-error-100 cursor-pointer transition duration-150"
                                            >
                                                <x-heroicon name="trash" />
                                                <span>Delete</span>
                                            </button>

                                            <button
                                                    type="button"
                                                    wire:click.prevent="$dispatch('review-user', { id: {{ $customer->id }}, type: 'customer' })"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-secondary-warning hover:bg-secondary-warning-100 cursor-pointer transition duration-150"
                                            >
                                                <x-heroicon name="star"/>
                                                <span>Review</span>
                                            </button>
                                        </div>
                                    </div>
                                </x-slot:content>
                            </x-form.dropdown-button>
                        </div>
                    </div>

                    {{-- Main info --}}
                    <div class="text-sm text-primary-grey space-y-2">
                        <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                        <p><strong>DOB:</strong> {{ date_format($customer->dob, 'd-m-Y') }}</p>
                        <p><strong>Nationality:</strong> {{ $customer->nationality }}</p>
                        <div class="space-y-1">
                            <p><strong>Review:</strong></p>
                            <div class="flex justify-start items-center">
                                @if(!empty($customer->reviews_count))
                                    @php $rating = round($customer->reviews_avg_rating) @endphp
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <x-heroicon
                                                        size="md"
                                                        name="star"
                                                        variant="solid"
                                                        class="{{ $i <= $rating ? 'text-secondary-warning' : 'text-outline-grey' }}"
                                                />
                                            @endfor
                                        </div>
                                    <p class="ml-2">{{number_format($customer->reviews_avg_rating, 1)}} / ({{ $customer->reviews_count }})</p>
                                @else
                                    <p> N.A </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="-z-10 p-6 bg-white flex flex-col xs:flex-row items-center xs:justify-between">
        {{ $customers->links('components.pagination') }}
    </div>
</div>
