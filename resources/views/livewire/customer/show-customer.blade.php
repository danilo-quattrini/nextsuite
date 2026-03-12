<div class="container mx-auto px-6 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <livewire:user.info-header
                :user="$customer"
                :has-review="true"
                :has-soft-skill="true"
                :show-info="false"
        />
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
    <div class="user-view__grid">

        {{-- PERSONAL INFO --}}
        <livewire:customer.customer-info :customer="$customer"/>

        {{-- ATTRIBUTES --}}
        <livewire:customer.customer-attributes :customer="$customer"/>

        {{-- REVIEWS --}}
        <livewire:customer.customer-reviews :customer="$customer"/>

    </div>

    {{-- HARD SKILLS --}}
    <livewire:skill.hard-skills :user="$customer"/>

    {{-- SOFT SKILL  --}}
    <livewire:customer.customer-soft-skills :customer="$customer" />

    <livewire:skill-modal :user="$customer"/>

    <x-popup.delete-popup :show-delete-modal="$showDeleteModal"/>
    <x-popup.review-popup :show-modal="$showModal" :rating="$rating" />
</div>