@props([
    'showReviewModal' => false,
    'rating' => 1,
])
@if($showReviewModal)
    {{-- REVIEW POP UP --}}
    <x-popup-box modal="showReviewModal">
        <x-slot:header>
            <div class="w-16 h-16 flex justify-center items-center bg-secondary-warning-100 rounded-full border border-secondary-warning">
                <x-heroicon size="lg"  class="text-secondary-warning" name="star" variant="solid" />
            </div>
        </x-slot:header>
        <x-slot:subheader>
            Leave a Review
        </x-slot:subheader>

        {{-- RATING --}}
        <div>
            <x-form.label-container label="Rating"/>
            <div class="flex items-center space-x-1 my-3">
                @for($i = 1; $i <= 5; $i++)
                    <button
                            type="button"
                            wire:click="$set('rating', {{ $i }})"
                            class="focus:outline-none cursor-pointer"
                    >
                        <x-heroicon
                                size="xxl"
                                name="star"
                                variant="solid"
                                class="{{ $rating >= $i ? 'text-secondary-warning' : 'text-outline-grey' }}"
                        />
                    </button>
                @endfor
            </div>
        </div>

        {{-- TEXTBOX --}}
        <x-textbox wire:model="review" label="Review" id="review" name="review" placeholder="Review here..."/>

        <x-slot:message>
            Write what you think about this user, your idea or opinion
        </x-slot:message>

        <div class="flex justify-between">
            <x-button
                    variant="disable"
                    size="large"
                    wire:click="$set('showReviewModal', false)"
            >
                Cancel
            </x-button>

            <x-button
                    variant="warning"
                    size="large"
                    wire:click="saveReview"
            >
                Review
            </x-button>
        </div>
    </x-popup-box>
@endif
