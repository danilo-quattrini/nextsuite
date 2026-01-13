@props(['customers' => null])
<div
        x-data="{ view: 'table' }"
        class="antialiased font-sans "
>
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Customers</h2>
            </div>
            <div class="flex gap-2 p-1 border-none bg-outline-grey rounded-md max-w-fit">
                <button
                        @click="view = 'table'"
                        :class="view == 'table' ? 'bg-white' : ''"
                        class="p-2 rounded-md"
                >
                    <x-heroicon name="list-bullet" size="lg"/>
                </button>
                <button
                        @click="view = 'card'"
                        :class="view == 'card' ? 'bg-white' : ''"
                        class="p-2 rounded-md"
                >
                    <x-heroicon name="squares-2x2" size="lg"/>
                </button>

            </div>

            {{-- TABLE VIEW --}}
            <div
                    x-show="view === 'table'"
                    class="mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
                    x-transition
            >
                <div class="inline-block min-w-full shadow rounded-md overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                        <tr>
                            <th
                                    class="table-head">
                                User
                            </th>
                            <th
                                    class="table-head">
                                Email
                            </th>
                            <th
                                    class="table-head">
                                Phone
                            </th>
                            <th
                                    class="table-head">
                                DOB
                            </th>
                            <th
                                    class="table-head">
                                Gender
                            </th>
                            <th
                                    class="table-head">
                                Nationality
                            </th>
                            <th
                                    class="table-head">
                                Reviews
                            </th>
                            <th
                                    class="table-head">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td class="p-6 bg-white text-sm">
                                    <div class="flex items-center gap-6">
                                        <div class="shrink-0 w-10 h-10">
                                            <img class="w-full h-full rounded-full"
                                                 src="{{ $customer->profile_photo_url ? asset('storage/customers-profile-photos/' . $customer->profile_photo_url) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->full_name) . '&color=5E81F4&background=5E81F440' }}"
                                                 alt="profile-picture" />
                                        </div>
                                        <div>
                                            <p class="table-text">
                                                {{ $customer->full_name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 bg-white text-sm">
                                    <p class="table-text">{{ $customer->email}}</p>
                                </td>
                                <td class="p-6 bg-white text-sm">
                                    <p class="table-text">{{ $customer->phone}}</p>
                                </td>
                                <td class="p-6 bg-white text-sm">
                                    <p class="table-text">{{ date_format($customer->dob, 'd-m-Y')}}</p>
                                </td>
                                <td class="p-6 bg-white text-sm">
                                    <p class="table-text">{{ $customer->gender  }}</p>
                                </td>
                                <td class="p-6 bg-white text-sm">
                                    <p class="table-text">{{ $customer->nationality  }}</p>
                                </td>
                                <td class="p-6 bg-white text-sm">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon
                                                name="star"
                                                class="text-secondary-warning"
                                        />
                                        <span class="table-text">
                                            {{ number_format($customer->reviews_avg_rating ?? 0, 1) }}
                                            ({{ $customer->reviews_count }})
                                        </span>
                                    </div>
                                </td>
                                <td class="p-6 bg-white text-sm">
                                    <div class="flex items-center columns-1 gap-5">
                                        <a href="#">
                                            <x-heroicon class="text-primary" name="information-circle"/>
                                        </a>
                                        <a href="#" wire:click="confirmDelete({{ $customer->id }})">
                                            <x-heroicon class="text-secondary-error" name="trash"/>
                                        </a>
                                        <a href="#">
                                            <x-heroicon class="text-primary-grey" name="pencil-square"/>
                                        </a>
                                        <a
                                                href="#"
                                                wire:click.prevent="
                                                    $dispatch('review-user', {
                                                        id: {{ $customer->id }},
                                                        type: 'customer'
                                                    })"
                                        >
                                            <x-heroicon class="text-secondary-warning" name="star" />
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    <div class="p-6 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>

            {{-- CARD VIEW --}}
            <div
                    x-show="view === 'card'"
                    x-transition
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6"
            >
                @foreach($customers as $customer)
                    <div class="bg-white rounded-md border border-outline-grey p-6 flex flex-col gap-4">

                        {{-- Header --}}
                        <div class="flex items-center gap-4">
                            <img
                                    class="w-12 h-12 rounded-full"
                                    src="{{ $customer->profile_photo_url
                                            ? asset('storage/customers-profile-photos/' . $customer->profile_photo_url)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($customer->full_name) . '&color=5E81F4&background=5E81F440'
                                    }}"
                             alt="customer profile picture"/>
                            <div>
                                <p class="font-semibold">{{ $customer->full_name }}</p>
                                <p class="text-sm text-primary-grey">{{ $customer->email }}</p>
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

                        {{-- Actions --}}
                        <div class="flex justify-between items-center pt-3 border-t">
                            <div class="flex gap-3">
                                <x-heroicon class="text-primary cursor-pointer" name="information-circle"/>
                                <x-heroicon class="text-primary-grey cursor-pointer" name="pencil-square"/>
                                <x-heroicon
                                        class="text-secondary-warning cursor-pointer"
                                        name="star"
                                        wire:click.prevent="$dispatch('review-user', { id: {{ $customer->id }}, type: 'customer' })"
                                />
                            </div>

                            <button
                                    wire:click="confirmDelete({{ $customer->id }})"
                                    class="text-secondary-error"
                            >
                                <x-heroicon name="trash"/>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>