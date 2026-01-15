<div>
    {{-- EMPTY STATE --}}
    @if(empty($templates))
        <div class="bg-white border border-outline-grey rounded-md p-10 text-center space-y-4">
            <x-heroicon
                    name="document-text"
                    class="mx-auto text-primary-grey"
                    size="xl"
            />

            <h3 class="text-lg font-semibold">
                No templates yet
            </h3>

            <p class="text-sm text-primary-grey mx-auto">
                Templates allow you to generate structured documents quickly.
                Create your first template to get started.
            </p>

            <x-button size="auto" href="#">
                Create first template
            </x-button>
        </div>
    @else

        {{-- TEMPLATE LIST --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($templates as $template)
                <div class="bg-white border border-outline-grey rounded-md p-6 flex flex-col justify-between">

                    {{-- HEADER --}}
                    <div class="space-y-2">
                        <h3 class="font-semibold text-lg">
                            {{ $template->name ?? 'Example name' }}
                        </h3>

                        <p class="text-sm text-primary-grey line-clamp-3">
                            {{ $template->description ?? 'No description provided.' }}
                        </p>
                    </div>

                    {{-- META --}}
                    <div class="text-sm text-primary-grey mt-4 space-y-1">
                        <p>
                            <strong>Created:</strong>
                            {{ $template->created_at->format('d M Y') }}
                        </p>
                        <p>
                            <strong>Updated:</strong>
                            {{ $template->updated_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex items-center justify-between mt-6 pt-4 border-t">

                        <a
                                href="{{ route('document-templates.show', $template) }}"
                                class="text-primary text-sm font-medium"
                        >
                            View
                        </a>

                        <x-form.dropdown-button align="right">
                            <x-slot:trigger>
                                <button
                                        type="button"
                                        class="flex items-center justify-center w-9 h-9 rounded-md border border-outline-grey hover:bg-outline-grey transition"
                                >
                                    <x-heroicon
                                            name="ellipsis-vertical"
                                            class="text-primary-grey"
                                    />
                                </button>
                            </x-slot:trigger>

                            <x-slot:content>
                                <div class="flex flex-col space-y-2 min-w-40">

                                    <a
                                            href="{{ route('document-templates.edit', $template) }}"
                                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm hover:bg-outline-grey transition"
                                    >
                                        <x-heroicon name="pencil-square" />
                                        <span>Edit</span>
                                    </a>

                                    <button
                                            wire:click="confirmDelete({{ $template->id }})"
                                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-secondary-error hover:bg-secondary-error-100 transition"
                                    >
                                        <x-heroicon name="trash" />
                                        <span>Delete</span>
                                    </button>

                                </div>
                            </x-slot:content>
                        </x-form.dropdown-button>
                    </div>

                </div>
            @endforeach

        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $templates->links() }}
        </div>

    @endif

</div>