@props([
    'showModal' => false,
    'rating'
])
@if($showModal)
    {{-- REVIEW POP UP --}}
    <x-popup-box modal="showModal">
        <x-slot:header>
            <div class="w-16 h-16 flex justify-center items-center bg-secondary-warning-100 rounded-full border border-secondary-warning">
                <x-heroicon size="lg" class="text-secondary-warning" name="star" variant="solid"/>
            </div>
        </x-slot:header>
        <x-slot:subheader>
            Leave a Review
        </x-slot:subheader>

        {{-- RATING --}}
        <div>
            <x-form.label-container label="Rating"/>
            <livewire:rating-stars wire:model="rating" size="xl" :selectable="true"/>
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
                    wire:click="$dispatch('close-modal')"
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
