<x-app-layout>
    <div class="container mx-auto px-6 py-8 space-y-8">
        <div class="py-8">
            <h2 class="text-3xl font-semibold text-black">
                Templates
            </h2>
            <p class="text-sm text-primary-grey mt-1">
                Create a new Template for your documents
            </p>
        </div>
        @livewire('template.show-templates')
    </div>
</x-app-layout>
