<div class="flex h-screen bg-white">

    {{-- LEFT PANEL — SECTIONS --}}
    <aside class="w-72 bg-white border-r px-4 py-6 overflow-y-auto">
        <h2 class="text-sm font-semibold text-black mb-4 tracking-wide">
            Sections
            <hr class="border border-outline-grey  mb-2"/>
        </h2>

        <div class="space-y-3">
            @foreach ($availableSections as $section)
                <div
                        draggable="true"
                        @dragstart="
                        $dispatch('section-drag', {
                            type: '{{ $section['type'] }}'
                        })
                    "
                        class="flex items-center gap-3 px-3 py-2 rounded-md border border-outline-grey bg-white
                           hover:bg-outline-grey shadow-md transition cursor-grab active:cursor-grabbing"
                >

                    <span class="text-sm font-medium text-black">
                        {{ $section['name'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </aside>

    {{-- MAIN EDITOR --}}
    <main class="flex-1 overflow-auto p-10">
        <div class="flex justify-center">

        {{-- PAGE WRAPPER --}}
            <div
                    class="origin-top"
                    style="transform: scale(0.85)"
            >

                {{-- A4 CANVAS --}}
                <div
                        class="relative bg-white shadow-lg border border-outline-grey rounded-md"
                        style="width: 794px; height: 1123px"
                        @dragover.prevent
                        @drop.prevent="
                        if ($event.detail?.type) {
                            $wire.addSection($event.detail.type)
                        }
                    "
                >

                    {{-- GRID (optional visual aid) --}}
                    <div class="absolute inset-0 grid grid-cols-12 grid-rows-12 pointer-events-none opacity-5">
                        @for ($i = 0; $i < 144; $i++)
                            <div class="border"></div>
                        @endfor
                    </div>

                    {{-- SECTIONS --}}
                    @foreach ($structure['pages'][0]['sections'] ?? [] as $index => $section)
                        <div
                                class="absolute border border-indigo-300 bg-indigo-50 rounded
                                   flex items-center justify-center text-xs font-medium text-indigo-700"
                                style="
                                left: {{ $section['x'] * 66 }}px;
                                top: {{ $section['y'] * 90 }}px;
                                width: {{ $section['w'] * 66 }}px;
                                height: {{ $section['h'] * 90 }}px;
                            "
                        >
                            {{ strtoupper($section['type']) }}
                        </div>
                    @endforeach

                    {{-- EMPTY STATE --}}
                    @if (empty($structure['pages'][0]['sections']))
                        <div class="absolute inset-0 flex items-center justify-center text-primary-grey text-sm">
                            Drag sections here to compose your layout
                        </div>
                    @endif

                </div>

                {{-- ACTION BAR --}}
                <div class="mt-6 flex justify-end gap-3">
                    <x-button
                            wire:click="save"
                            size="auto"

                    >
                        Save layout
                    </x-button>
                </div>

            </div>
        </div>
    </main>
</div>