<x-app-layout>
    <div class="page-content__container">
        <div class="page-content__hero">
            <div class="page-content__hero-inner">
                <div class="page-content__hero-row">
                    <div class="page-content__hero-copy">
                        <h2 class="page-content__title">
                            Document Generation
                        </h2>
                        <p class="page-content__subtitle">
                            Select a customer to generate documents and manage existing files.
                        </p>
                    </div>
                    <div class="page-content__stats">
                        <div class="page-content__stat-card">
                            <p class="page-content__stat-label">Customers</p>
                            <p class="page-content__stat-value">{{ $customers->total() }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="page-content__body">
            <div class="page-content__grid">
                @foreach($customers as $customer)
                    @php
                        $hasDocuments = $customer->documents()->exists();
                    @endphp
                    <div class="page-content__card group">
                        <div class="page-content__card-info">
                            <div class="page-content__card-header">
                                <div class="page-content__profile">
                                    <div class="page-content__avatar">
                                        <x-profile-image
                                            :src="$customer->profile_photo_url"
                                            :name="$customer->full_name"
                                            directory="customer-profile-photos"
                                            size="custom"
                                            class="page-content__avatar-image"
                                        />
                                    </div>
                                    <div>
                                        <p class="page-content__name">
                                            {{ $customer->full_name }}
                                        </p>
                                        <p class="page-content__email">
                                            {{ $customer->email }}
                                        </p>
                                    </div>
                                </div>
                                @if($hasDocuments)
                                    <x-tag variant="green">
                                        Has documents
                                    </x-tag>
                                @else
                                    <x-tag variant="yellow">
                                        No documents yet
                                    </x-tag>
                                @endif
                            </div>

                            <div class="page-content__meta">
                                <p><span class="page-content__meta-label">Phone:</span> {{ $customer->phone ?? '—' }}</p>
                            </div>
                        </div>

                        {{--  DOCUMENT OPERATIONS --}}
                        <div class="page-content__actions">
                            @if($hasDocuments)
                                <x-form.dropdown-button>
                                    <x-slot:trigger>
                                        <x-button size="auto" variant="rest">
                                            <x-heroicon name="document-magnifying-glass"/>
                                            Documents
                                            <x-heroicon
                                                    name="chevron-down"
                                                    size="md"
                                                    class="transition-transform"
                                                    x-bind:class="open ? 'rotate-180' : ''"
                                            />
                                        </x-button>
                                    </x-slot:trigger>

                                    <x-slot:content>
                                        <div class="page-content__dropdown-list">
                                                @foreach($customer->documents as $document)
                                                    <a href="{{ route('document.show', $document->id) }}">
                                                        <div class="page-content__dropdown-item">
                                                            <x-heroicon name="document-text"/>
                                                            {{ ucfirst($document->type) . ' ' . ' ' . $customer->full_name ?? 'Document' }}
                                                        </div>
                                                    </a>
                                                @endforeach
                                        </div>
                                    </x-slot:content>
                                </x-form.dropdown-button>
                            @endif
                            <x-button
                                    onclick="{{$showDocumentGenerationModal = false}}"
                                    href="{{ route('document.create', ['customer' => $customer->id]) }}"
                                    size="full"
                            >
                                <x-heroicon name="document-plus"/>
                                New Document
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="page-content__pagination">
                {{ $customers->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
