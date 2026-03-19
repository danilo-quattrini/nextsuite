@if ($paginator->hasPages())
    <nav class="flex items-center justify-center" role="navigation" aria-label="Pagination">
        <div class="flex items-center gap-8 px-4 py-2">
            @if ($paginator->onFirstPage())
                <span class="flex items-center gap-2 px-3 py-2 rounded-md text-primary-grey bg-white cursor-not-allowed">
                    <x-heroicon name="chevron-left" class="text-primary-grey" />
                    <span>Prev</span>
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    class="flex items-center gap-2 px-3 py-2 rounded-md text-black hover:bg-outline-grey transition"
                >
                    <x-heroicon name="chevron-left" class="text-black" />
                    <span>Prev</span>
                </a>
            @endif

            <div class="flex items-center gap-4">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 text-outline-grey">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    aria-current="page"
                                    class="w-9 h-9 flex items-center justify-center rounded-md bg-primary text-white font-semibold"
                                >
                                    {{ $page }}
                                </span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-md text-black hover:border hover:border-primary hover:text-primary transition font-semibold"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    class="flex items-center gap-2 px-3 py-2 rounded-md text-black hover:bg-outline-grey transition"
                >
                    <span>Next</span>
                    <x-heroicon name="chevron-right" class="text-primary-grey" />
                </a>
            @else
                <span class="flex items-center gap-2 px-3 py-2 rounded-md text-primary-grey bg-white cursor-not-allowed">
                    <span>Next</span>
                    <x-heroicon name="chevron-right" class="text-primary-grey" />
                </span>
            @endif
        </div>
    </nav>
@endif
