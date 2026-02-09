@php use Illuminate\Support\Carbon; @endphp
@props([
    'user' => null,
    'href' => null
])
<a @if(!empty($href) && !empty($user)) href="{{ route($href, $user) }}" @endif >
    <div
            class="page-content__card page-content__card-info page-content__card-animated"
    >
        {{-- Header --}}
        <div class="page-content__card-header">
            <div class="page-content__profile">
                <x-profile-image
                        :src="$user->profile_photo_url"
                        :name="$user->full_name"
                        directory="users-profile-photos"
                        size="custom"
                        class="page-content__avatar-image"
                />

                <div>
                    <p class="page-content__name">{{ $user->full_name }}</p>
                    <p class="page-content__email">{{ $user->email }}</p>
                </div>
            </div>
        </div>
{{-- TODO:  FIX THE DROPDOWN MENU       --}}
{{--        <div--}}
{{--                class="flex float-end"--}}
{{--                onclick="event.stopPropagation()"--}}
{{--        >--}}
{{--            <x-form.dropdown-button align="right">--}}
{{--                <x-slot:trigger>--}}
{{--                    <x-button--}}
{{--                            type="button"--}}
{{--                            variant="white"--}}
{{--                            size="auto"--}}
{{--                            aria-label="Customer actions 2"--}}
{{--                    >--}}
{{--                        <x-heroicon--}}
{{--                                name="ellipsis-vertical"--}}
{{--                        />--}}
{{--                    </x-button>--}}
{{--                </x-slot:trigger>--}}

{{--                <x-slot:content>--}}
{{--                    <div class="flex-col items-center space-y-3">--}}
{{--                        <div class="flex flex-col space-y-2 min-w-40">--}}

{{--                            <a--}}
{{--                                    href="#"--}}
{{--                                    class="flex items-center gap-2 px-3 py-2 rounded-md text-sm hover:bg-outline-grey transition duration-150"--}}
{{--                            >--}}
{{--                                <x-heroicon name="pencil-square" class="text-primary-grey" />--}}
{{--                                <span>Edit customer</span>--}}
{{--                            </a>--}}

{{--                            <button--}}
{{--                                    type="button"--}}
{{--                                    wire:click.prevent="$dispatch('delete-element', { id: {{ $user->id }}, type: 'customer' })"--}}
{{--                                    class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-secondary-error hover:bg-secondary-error-100 cursor-pointer transition duration-150"--}}
{{--                            >--}}
{{--                                <x-heroicon name="trash" />--}}
{{--                                <span>Delete</span>--}}
{{--                            </button>--}}

{{--                            <button--}}
{{--                                    type="button"--}}
{{--                                    wire:click.prevent="$dispatch('review-user', { id: {{ $user->id }}, type: 'customer' })"--}}
{{--                                    class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-secondary-warning hover:bg-secondary-warning-100 cursor-pointer transition duration-150"--}}
{{--                            >--}}
{{--                                <x-heroicon name="star"/>--}}
{{--                                <span>Review</span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </x-slot:content>--}}
{{--            </x-form.dropdown-button>--}}
{{--        </div>--}}

        {{-- Main info --}}
        <div class="page-content__meta">
            <p><span class="page-content__meta-label">Phone:</span> {{ $user->phone ?: '---' }}</p>
            <p><span class="page-content__meta-label">DOB:</span> {{ $user->dob ? Carbon::parse($user->dob)->format('d-m-Y') : '---' }}</p>
            <p><span class="page-content__meta-label">Nationality:</span> {{ $user->nationality ?: '---' }}</p>
            <div class="space-y-1">
                <div class="page-content__meta-label">
                    @if(!empty($user->reviews_count))
                        @php $rating = round($user->reviews_avg_rating) @endphp
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <x-heroicon
                                        size="md"
                                        name="star"
                                        variant="solid"
                                        class="{{ $i <= $rating ? 'text-secondary-warning' : 'text-outline-grey' }}"
                                />
                            @endfor
                            <p>{{number_format($user->reviews_avg_rating, 1)}} / ({{ $user->reviews_count }})</p>
                        </div>
                    @else
                        <p> N.A </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</a>