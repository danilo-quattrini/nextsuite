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
</div>
