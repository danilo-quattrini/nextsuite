@if ($paginator->hasPages())
    <nav class="flex items-center justify-center" role="navigation" aria-label="Pagination">
        <div class="flex items-center gap-8 px-4 py-2">
            @if ($paginator->onFirstPage())
                <x-button
                        variant="disable"
                        size="full"
                        :disabled="true"
                >
                    <x-heroicon name="chevron-left"/>
                </x-button>
            @else
                <x-button
                        variant="white"
                        size="full"
                        wire:click="previousPage"
                >
                    <x-heroicon name="chevron-left" />
                </x-button>
            @endif

            <div class="flex items-center gap-4">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 text-outline-grey">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <x-button
                                        size="icon"
                                        variant="primary"
                                >
                                    {{ $page }}
                                </x-button>
                            @else
                                <x-button
                                        size="icon"
                                        variant="white"
                                        wire:click="gotoPage({{ $page }})"
                                >
                                    {{ $page }}
                                </x-button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <x-button
                        variant="white"
                        size="full"
                        wire:click="nextPage"
                >
                    <x-heroicon name="chevron-right" />
                </x-button>
            @else
                <x-button
                        size="full"
                        variant="disable"
                        :disabled="true"
                >
                    <x-heroicon name="chevron-right" />
                </x-button>
            @endif
        </div>
    </nav>
@endif