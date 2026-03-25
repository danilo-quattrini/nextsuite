{{--
    user-card.blade.php

    Props
    ─────
    user       Model   required  The user/model to display.
    href       string  optional  Named route for the card link.
    compact    bool    false     Use the compact card variant (less padding).
    showMeta   bool    true      Show the phone/DOB/nationality/role block.
    showRating bool    true      Show the star-rating block.

    Slot
    ────
    $slot  optional  Extra content injected after the actions area.
--}}

@php
    use Illuminate\Support\Carbon;
    $modelType = get_class($user) ?? ' ';
    $modelName = strtolower(class_basename($modelType)) ?? ' ';
@endphp

@props([
    'user'       => null,
    'href'       => null,
    'compact'    => false,
    'showMeta'   => true,
    'showRating' => true,
])

{{-- CHANGE 11: render <div> instead of bare <a> when href/user are absent --}}
@php
    $isLink    = !empty($href) && !empty($user);
    $tag       = $isLink ? 'a' : 'div';
    $routeAttr = $isLink ? 'href="'.route($href, $user).'"' : '';
    $cardClass = 'page-content__card page-content__card__animation'
               . ($compact ? ' page-content__card--compact' : '');
@endphp

<{{ $tag }} {!! $routeAttr !!}>

<div class="{{ $cardClass }}">

    {{-- ── Header ──────────────────────────────────────── --}}
    <div class="page-content__card-header page-content__card-info">
        <div class="page-content__profile">
            <x-profile-image
                    :src="$user?->profile_photo_url"
                    :name="$user->full_name"
                    directory="{{ $modelName }}-profile-photos"
                    size="custom"
                    class="page-content__avatar-image"
            />

            <div class="min-w-0"> {{-- min-w-0 lets truncate work inside flex --}}
                <p class="page-content__name">{{ $user->full_name }}</p>
                <p class="page-content__email">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    {{-- ── Meta block (CHANGE 9: hidden when showMeta=false) ── --}}
    @if($showMeta)
        <div class="page-content__meta mt-4">
            <p>
                <span class="page-content__meta-label">Phone:</span>
                {{ $user->phone ?: '---' }}
            </p>
            <p>
                <span class="page-content__meta-label">DOB:</span>
                {{ $user->dob ? Carbon::parse($user->dob)->format('d-m-Y') : '---' }}
            </p>
            <p>
                <span class="page-content__meta-label">Nationality:</span>
                {{ $user->nationality ?: '---' }}
            </p>
            <p>
                    <span class="page-content__meta-label">
                        Role{{ count($user->getRoleNames()) > 1 ? 's' : '' }}:
                    </span>
                {{ ucfirst($user->getRoleNames()->first()) ?: '---' }}
            </p>

            {{-- ── Rating (CHANGE 10: hidden when showRating=false) ── --}}
            @if($showRating)
                <div class="space-y-1">
                    <div class="page-content__meta-label">
                        @if(!empty($user->reviews_count))
                            @php $rating = round($user->reviews_avg_rating) @endphp
                            <div class="flex items-center space-x-2">
                                <livewire:rating-stars :rating="$rating" size="lg"/>
                                <p>
                                    {{ number_format($user->reviews_avg_rating, 1) }} / 5
                                    ({{ $user->reviews_count }})
                                </p>
                            </div>
                        @else
                            <p>N.A</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- ── Caller-injected content (CHANGE 11: $slot) ──────── --}}
    @if($slot->isNotEmpty())
        <div class="page-content__actions">
            {{ $slot }}
        </div>
    @endif

</div>

</{{ $tag }}>