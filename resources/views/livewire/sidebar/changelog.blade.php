<div>
    <div class="page-content__container">
    {{-- HEADER --}}
        <div class="page-content__hero">
            <div class="page-content__hero-inner">
                <div class="page-content__hero-row">
                    <div class="page-content__hero-copy">
                        <h2 class="page-content__title">
                            {{ __('Changelog') }}
                        </h2>
                        <p class="page-content__subtitle">
                            {{__('Here we have a set of actions made by the all the users')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content__body">
            <section class="user-view__grid">
                @foreach($changeLogs as $changeLog)
                    <div class="user-view__panel user-view__panel--wide changelog__panel changelog__panel--{{ $this->logTypeKey($changeLog) }}">
                        <div class="user-view__panel-header">
                            <h5>{{ $this->logState($changeLog) }}</h5>
                            <span class="user-view__panel-tag changelog__tag changelog__tag--{{ $this->logTypeKey($changeLog) }}">{{ $this->logState($changeLog) }}</span>
                        </div>
                        <div class="user-view__timeline">
                            <div class="user-view__timeline-item">
                                <span class="user-view__dot changelog__dot changelog__dot--{{ $this->logTypeKey($changeLog) }}"></span>
                                <div>
                                    <p class="user-view__timeline-title">{{ $this->logState($changeLog) }} {{ $this->logSubjectLabel($changeLog) }}</p>
                                    <p class="user-view__timeline-meta">{{ $this->logCauserLabel($changeLog) }} · {{ $this->logTimestamp($changeLog) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
    </div>
</div>
