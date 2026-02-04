<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @foreach($customers as $customer)
            <div
                    class="bg-white rounded-md border border-outline-grey p-6 flex flex-col gap-4
                            cursor-pointer hover:shadow-md transition"
                    onclick="window.location='{{ route('report.show', $customer) }}'"
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

    <div class="-z-10 p-6 bg-white flex flex-col xs:flex-row items-center xs:justify-between">
        {{ $customers->links('components.pagination') }}
    </div>
</div>