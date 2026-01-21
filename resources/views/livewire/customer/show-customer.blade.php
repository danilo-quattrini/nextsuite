<div class="container mx-auto px-6 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div class="flex-col justify-start items-center space-y-6">
            <img
                    class="w-40 h-40 rounded-full"
                    src="{{ $customer->profile_photo_url
                    ? asset('storage/customers-profile-photos/' . $customer->profile_photo_url)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($customer->full_name) . '&color=5E81F4&background=5E81F440'
                }}"
                    alt="user profile picture"
            />


            <div>
                <h1 class="text-2xl font-semibold">
                    {{ $customer->full_name }}
                </h1>

                <div class="flex items-center gap-2 mt-1 text-sm text-primary-grey">
                    <x-heroicon name="star" class="text-secondary-warning" />
                    <span class="font-medium">
                        {{ number_format($customer->reviews_avg_rating ?? 0, 1) }}
                    </span>
                    <span>
                        ({{ $customer->reviews_count }} reviews)
                    </span>
                </div>
            </div>
        </div>

        <x-button size="auto" href="#">
            <x-heroicon name="pencil-square"/>
            Edit customer
        </x-button>
    </div>

    {{-- INFO GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Personal Info --}}
        <div class="bg-white border border-outline-grey rounded-md p-6 space-y-3">
            <h3 class="font-semibold text-lg border-b border-b-outline-grey">Personal Information</h3>

            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Date of birth:</strong> {{ $customer->dob->format('d M Y') }}</p>
            <p><strong>Gender:</strong> {{ $customer->gender }}</p>
            <p><strong>Nationality:</strong> {{ $customer->nationality }}</p>
        </div>

        {{-- Attributes --}}
        <div class="bg-white border border-outline-grey rounded-md p-6 space-y-3">
            <h3 class="font-semibold text-lg border-b border-b-outline-grey">Attributes</h3>

            <ul class="space-y-2 text-sm text-primary-grey">
                @foreach($customerAttributes as $attribute )
                    <li> {{ $attribute->name . ': ' . $attribute->pivot->value }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- FIELD COMPETENCE --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-outline-grey rounded-md p-6 space-y-4">
            <h3 class="font-semibold text-lg border-b border-b-outline-grey">Soft Skills</h3>
            <div class="flex justify-center items-center">
                <div>
                    {!! $this->chart->render() !!}
                </div>
            </div>
        </div>

        {{-- Category Skills --}}
        <div class="bg-white border border-outline-grey rounded-md p-6 space-y-3">
            <h3 class="font-semibold text-lg border-b border-b-outline-grey">Field Skills</h3>

            <div class="flex flex-wrap">
                <div class="flex-col space-y-3">
                    <p class="text-sm text-primary-grey"> Technical</p>
                    @foreach($customerSkills as $skill)
                        @if($skill->category->name != "Abilities")
                            <span class="px-3 py-1 rounded-full bg-outline-grey text-sm mr-2">
                                    {{ $skill->name }}
                                </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- REVIEWS --}}
    <div class="bg-white border border-outline-grey rounded-md p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-lg">Reviews</h3>

            <x-button
                    size="auto"
                    wire:click="$dispatch('review-user', { id: {{ $customer->id }}, type: 'customer' })"
            >
                Add review
            </x-button>
        </div>

        @forelse($customer->reviews as $review)
            <div class="border-t border-outline-grey pt-4">
                <div class="flex items-center gap-2 mb-1">
                    <x-heroicon name="star" class="text-secondary-warning" />
                    <span class="font-medium">{{ $review->rating }}/5</span>
                </div>
                <p class="text-sm text-primary-grey">
                    {{ $review->comment }}
                </p>
            </div>
        @empty
            <p class="text-sm text-primary-grey">
                No reviews yet.
            </p>
        @endforelse
    </div>

</div>