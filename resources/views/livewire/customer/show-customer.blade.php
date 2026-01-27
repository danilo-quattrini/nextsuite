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
                <div class="flex justify-start items-center gap-4 ">
                    <h1 class="text-2xl font-semibold">
                        {{ $customer->full_name }}
                    </h1>
                    <x-average-tag :value="$softSkillsAverage" />
                </div>
                <div class="flex items-center gap-2 mt-1 text-sm text-primary-grey">
                    <x-heroicon variant="solid" name="star"  class="text-secondary-warning" />
                    <span>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">

        {{-- Personal Info --}}
        <div class="bg-white border border-outline-grey rounded-md p-6 space-y-3">
            <h3 class="font-semibold text-lg border-b border-b-outline-grey">Personal Information</h3>

            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Date of birth:</strong> {{ $customer->dob->format('d M Y') }}</p>
            <p><strong>Gender:</strong> {{ $customer->gender }}</p>
            <p><strong>Nationality:</strong> {{ $customer->nationality }}</p>
        </div>

        {{-- ATTRIBUTES --}}
        <div class="bg-white border border-outline-grey rounded-md p-6 space-y-3">
            <div class="flex items-center justify-between border-b border-b-outline-grey pb-2">
                <h3>Attributes</h3>
                {{-- BUTTON TO ADD AN ATTRIBUTE --}}
                @livewire('attribute.attribute-modal')
            </div>

            <ul class="space-y-2 text-sm text-primary-grey">
                @forelse($customerAttributes as $attribute)
                    <li> {{ $attribute->name . ': ' . $attribute->pivot?->value }}</li>
                @empty
                    <p class="text-sm text-primary-grey">
                        No attributes yet.
                    </p>
                @endforelse
            </ul>
        </div>

    </div>

    {{-- Category Skills --}}
    <div class="bg-white border border-outline-grey rounded-md p-6 space-y-3">
        <h3 class="border-b border-b-outline-grey">Field Skills</h3>

        <div class="flex flex-wrap">
            <div class="flex-col space-y-3">
                <p class="text-primary-grey">Technical</p>
                <div class="grid grid-cols-4 justify-center items-center gap-4">
                    @foreach($customerSkills as $skill)
                        @if($skill->category->type->value != 'soft_skill')
                            <span class="px-3 py-2 text-center rounded-full bg-outline-grey truncate">
                                    {{ $skill->name }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- FIELD COMPETENCE --}}
    <div class="bg-white border border-outline-grey rounded-md p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-b-outline-grey pb-2">
            <h3 class="truncate">Soft Skills</h3>
            {{-- BUTTON TO ADD A NEW SKILL  --}}
            @livewire('skill-modal')
        </div>

        @if($softSkills && $softSkills->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($softSkills as $categoryName => $group)
                    @php
                        $labels = collect($group['skills'])->pluck('name')->all();
                        $data = collect($group['skills'])->pluck('level')->map(fn ($level) => $level ?? 0)->all();
                    @endphp
                    <div class="border border-outline-grey rounded-md p-4 bg-white">
                        <div class="flex justify-center mb-2">
                            <x-average-tag :value="$group['average']" />
                        </div>
                        <div class="flex items-start justify-center gap-4">
                            <h4 class="font-semibold uppercase tracking-wide">{{ $categoryName }}</h4>
                        </div>
                        <div class="flex justify-center items-center mt-2">
                            {!! $this->buildSoftSkillChart($categoryName, $labels, $data)->render() !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif(empty($softSkills))
            <p class="text-primary-grey">No soft skills yet.</p>
        @endif
    </div>

    {{-- REVIEWS --}}
    <div class="bg-white border border-outline-grey rounded-md p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-b-outline-grey">
            <h3> Reviews</h3>
        </div>

        @forelse($customer->reviews as $review)
            <div class="border-b border-outline-grey pb-4">

                <div class="flex justify-between items-center mb-4">
                    <div class="flex justify-center items-center gap-2 ">
                        <img class="size-8 rounded-full object-cover" src="{{ $review->author->profile_photo_url }}" alt="{{ Auth::user()->full_name }}" />
                        <span class="font-medium"> {{ $review->author->full_name }}</span>
                    </div>
                    <div class="flex justify-center items-center gap-1 bg-secondary-warning-100/50 px-2 py-1 rounded-md">
                        <x-heroicon name="star" variant="solid" class="text-secondary-warning" />
                        <span class="font-medium">{{ $review->rating }}</span>
                    </div>
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
