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
                    <x-average-tag size="xl" :value="$softSkillsAverage" />
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

        <x-form.dropdown-button align="right">
            <x-slot:trigger>
                <x-button
                        type="button"
                        variant="white"
                        size="auto"
                        aria-label="Customer options"
                >
                    <x-heroicon
                            name="cog-8-tooth"
                            size="xl"
                    />
                </x-button>
            </x-slot:trigger>
            <x-slot:content>
                <div class="flex-col items-center space-y-3">
                    <div class="flex flex-col space-y-2 min-w-40">
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
                    </div>
                </div>
            </x-slot:content>

        </x-form.dropdown-button>

    </div>

    {{-- INFO GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- PERSONAL INFO --}}
        <livewire:customer.customer-info :customer="$customer"/>

        {{-- ATTRIBUTES --}}
        <livewire:customer.customer-attributes :customer="$customer"/>

        {{-- REVIEWS --}}
        <livewire:customer.customer-reviews :customer="$customer"/>

    </div>

    {{-- FIELD SKILLS --}}
    <livewire:customer.customer-hard-skills :customer="$customer" />

    {{-- SOFT SKILL COMPETENCE --}}
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
                            <x-average-tag size="xl" :value="$group['average']" />
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
        @elseif($softSkills->isEmpty())
            <x-card border="dashed">
                <x-heroicon name="star" class="mx-auto h-12 w-12 text-primary-grey" />

                <h2 class="font-semibold text-black">
                    No soft skill associated
                </h2>

                <p class="text-primary-grey">
                    Your customer currently don’t have any soft-skill.
                </p>

                <div class="flex justify-center">
                    <x-button href="{{ route('skill-schema.create', $customer) }}" size="auto">
                        Create Skill
                    </x-button>
                </div>
            </x-card>
        @endif
    </div>

    <x-popup.delete-popup :show-delete-modal="$showDeleteModal"/>
    <x-popup.review-popup :show-review-modal="$showReviewModal" :rating="$rating" />
</div>