<div class="container mx-auto px-6 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div class="flex-col justify-start items-center space-y-6">
            <x-profile-image
                :src="$customer->profile_photo_url"
                :name="$customer->full_name"
                directory="customers-profile-photos"
            />


            <div>
                <div class="flex justify-start items-center gap-4 ">
                    <h1 class="text-2xl font-semibold">
                        {{ $customer->full_name }}
                    </h1>
                    <x-average-tag size="large" :value="$softSkillsAverage" />
                </div>
                <div class="flex items-center gap-2 mt-1 text-base text-primary-grey">
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

        <x-button variant="outline-primary" size="auto" href="#">
            <x-heroicon size="xl" name="pencil-square"/>
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
        <div class="flex justify-between items-center border-b border-b-outline-grey pb-2">
            <h3>Field Skills</h3>
            {{-- BUTTON TO ADD A NEW SKILL  --}}
            @livewire('skill-modal', ['hideSoftSkills' => true])
        </div>


        <div class="flex flex-wrap">
            <div class="flex-col space-y-3 w-full">

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($customerSkills as $skill)
                        @if($skill->category->type->value != 'soft_skill')
                            <div class="border border-outline-grey rounded-md p-4 bg-white">
                                <h4> {{ $skill->name }} </h4>
                                <div class="my-2">
                                    <span
                                            class="px-3 py-1.5 rounded-md text-xs font-medium border transition"
                                    >
                                        {{ $skill->category?->name ?: 'Uncategorized' }}
                                    </span>
                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                    <div class="space-y-2">
                                        <div class="space-y-2">
                                            <h6> Experience: </h6>
                                            <p class="text-base">{{ $skill->pivot?->years != null ?  $skill->pivot?->years . ' years'  : 'N.A' }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h6> Knowledge Level:</h6>
                                        <x-average-tag
                                                size="auto"
                                                :value="$skill->pivot?->level"
                                        />
                                    </div>
                                </div>
                            </div>
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
            @livewire('skill-modal', ['hideFieldSkills' => true])
        </div>

        @if($softSkills && $softSkills->isNotEmpty())
            <div class="flex gap-8 overflow-x-auto w-full pb-2">
                @foreach($softSkills as $categoryName => $group)
                    @php
                        $labels = collect($group['skills'])->pluck('name')->all();
                        $data = collect($group['skills'])->pluck('level')->map(fn ($level) => $level ?? 0)->all();
                    @endphp
                    <div class="border border-outline-grey rounded-md p-4 bg-white min-w-125 shrink-0">
                        <div class="flex justify-center my-6">
                            <x-average-tag size="large" :value="$group['average']" />
                        </div>
                        <div class="flex items-start justify-center">
                            <h3 class="font-semibold uppercase tracking-wide">{{ $categoryName }}</h3>
                        </div>
                        <div class="flex justify-center items-center">
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
                        <x-profile-image
                            :src="$review->author->profile_photo_url"
                            :name="$review->author->full_name"
                            size="custom"
                            class="size-8"
                        />
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
