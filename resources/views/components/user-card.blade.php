@php
    use Illuminate\Support\Carbon;
    $modelType = get_class($user) ?? ' ';
    $modelName = strtolower(class_basename($modelType)) ?? ' ';
@endphp
@props([
    'user' => null,
    'href' => null
])
<a @if(!empty($href) && !empty($user)) href="{{ route($href, $user) }}" @endif >
    <div
            class="page-content__card page-content__card-info page-content__card__animation"
    >
        {{-- Header --}}
        <div class="page-content__card-header">
            <div class="page-content__profile">
                <x-profile-image
                        :src="$user?->profile_photo_url"
                        :name="$user->full_name"
                        directory="{{ $modelName }}s-profile-photos"
                        size="custom"
                        class="page-content__avatar-image"
                />

                <div>
                    <p class="page-content__name">{{ $user->full_name }}</p>
                    <p class="page-content__email">{{ $user->email }}</p>
                </div>
            </div>
        </div>

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