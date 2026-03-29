{{--
    company-card.blade.php

    Props
    ─────
    company    Model   required  The company model to display.
    href       string  optional  Named route for the card link.
    compact    bool    false     Use the compact card variant (less padding).
    showMeta   bool    true      Show the email/phone/city/website block.
    showFields bool    true      Show the company fields/sectors tags.

    Slot
    ────
    $slot  optional  Extra content injected after the actions area.
--}}

@props([
    'company'    => null,
    'href'       => null,
    'compact'    => false,
    'showMeta'   => true,
    'showFields' => true,
])

@php
    $isLink    = !empty($href) && !empty($company);
    $tag       = $isLink ? 'a' : 'div';
    $routeAttr = $isLink ? 'href="'.route($href, $company).'"' : '';
    $cardClass = 'page-content__card'
               . ($compact ? ' page-content__card--compact' : '');
@endphp

<{{ $tag }} {!! $routeAttr !!}>

<div class="{{ $cardClass }}">

    {{-- ── Header ──────────────────────────────────────── --}}
    <div class="page-content__card-header page-content__card-info">
        <div class="page-content__profile">
            <x-profile-image
                    :src="$company?->company_photo"
                    :name="$company->name"
                    directory="company-profile-photos"
                    size="custom"
                    class="page-content__avatar-image"
            />

            <div class="min-w-0">
                <p class="page-content__name">{{ $company->name }}</p>
                <p class="page-content__email">{{ $company->email }}</p>
            </div>

        </div>
    </div>

    {{-- ── Meta information ────────────────────────────── --}}
    @if($showMeta)
        <div class="page-content__meta mt-4">
            <p>
                <span class="page-content__meta-label">Phone:</span>
                {{ $company->phone ?: '---' }}
            </p>

            <p>
                <span class="page-content__meta-label">City:</span>
                {{ $company->city ?: '---' }}
            </p>

            <p>
                <span class="page-content__meta-label">VAT:</span>
                {{ $company->vat_number ?: '---' }}
            </p>


            {{-- ── WEBSITE ────────────────── --}}
            <p>
                <span class="page-content__meta-label">Website:</span>
                @if($company->website)

                    <a href="{{ $company->website }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="page-content__link"
                       onclick="event.stopPropagation()">
                        Link
                    </a>

                @else
                    ---
                @endif
            </p>
            {{-- ── Fields / Sectors tags ────────────────── --}}
            @if($showFields && $company->relationLoaded('fields') && $company->fields->isNotEmpty())
                <div class="flex flex-wrap gap-xs mt-4">
                    @foreach($company->fields as $field)
                        <x-tag
                                variant="white"
                                size="sm"
                        >
                            {{ $field->name }}
                        </x-tag>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- ── Caller-injected content (slot) ─────────────── --}}
    @if($slot->isNotEmpty())
        <div class="page-content__actions">
            {{ $slot }}
        </div>
    @endif

</div>

</{{ $tag }}>