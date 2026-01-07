@props(['customers' => null])
<div class="antialiased font-sans ">
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Customers</h2>
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                        <tr>
                            <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                User
                            </th>
                            <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                            <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Phone
                            </th>
                            <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                DOB
                            </th>
                            <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Gender
                            </th>
                            <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nationality
                            </th>
                            <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Report
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td class="px-5 py-5 bg-white text-sm">
                                    <div class="flex items-center">
                                        <div class="shrink-0 w-10 h-10">
                                            <img class="w-full h-full rounded-full"
                                                 src="{{ $customer->profile_photo_url ? asset('storage/customers-profile-photos/' . $customer->profile_photo_url) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->full_name) . '&color=5E81F4&background=5E81F440' }}"
                                                 alt="profile-picture" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ $customer->full_name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $customer->email}}</p>
                                </td>
                                <td class="px-5 py-5 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $customer->phone}}</p>
                                </td>
                                <td class="px-5 py-5 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ date_format($customer->dob, 'd-m-Y')}}</p>
                                </td>
                                <td class="px-5 py-5 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $customer->gender  }}</p>
                                </td>
                                <td class="px-5 py-5 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $customer->nationality  }}</p>
                                </td>
                                <td class="px-5 py-5 bg-white text-sm">
                                    <x-button variant="rest" href="/generate/ {{ $customer->id }}">
                                        <x-heroicon name="document-arrow-down"/>
                                    </x-button>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between          ">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>