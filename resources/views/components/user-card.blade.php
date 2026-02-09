@props(['user' => null])

<div
        class="page-content__card page-content__card-info
                        cursor-pointer hover:shadow-md hover:-translate-y-2 transition-all"
        onclick="window.location='{{ route('report.show', $user) }}'"
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

    {{-- Main info --}}
    <div class="page-content__meta">
        <p><span class="page-content__meta-label">Phone:</span> {{ $user->phone ?: '---' }}</p>
        <p><span class="page-content__meta-label">DOB:</span> {{ date_format($user->dob, 'd-m-Y') ?: '---' }}</p>
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
                    </div>
                    <p class="ml-2">{{number_format($user->reviews_avg_rating, 1)}} / ({{ $user->reviews_count }})</p>
                @else
                    <p> N.A </p>
                @endif
            </div>
        </div>
    </div>
</div>
