@props(['customers' => null])
<div class="antialiased font-sans ">
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Customers</h2>
            </div>
            <div class="mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
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
                                    <div class="flex items-center columns-1 gap-5">
                                        <a href="/generate/{{ $customer->id }}">
                                            <x-heroicon name="document-arrow-down"/>
                                        </a>
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
        </div>
    </div>
</div>