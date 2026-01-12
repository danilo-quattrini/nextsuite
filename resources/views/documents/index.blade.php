<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <h2 class="text-3xl font-semibold text-black">
                Customers
            </h2>
            <p class="text-sm text-primary-grey mt-1">
                Select a customer to view details or generate documents
            </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($customers as $customer)
                <div class="bg-white border border-outline-grey rounded-md p-6 flex flex-col justify-between">

                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            @if(empty($customer->profile_photo_url))
                                <div class="h-12 w-12 rounded-full bg-secondary flex items-center justify-center text-sm font-semibold text-primary">
                                    {{ strtoupper(substr($customer->full_name, 0, 1)) }}
                                </div>
                            @else
                                <div class="shrink-0 w-12 h-12">
                                    <img class="w-full h-full rounded-full"
                                         src="{{ asset('storage/customers-profile-photos/' . $customer->profile_photo_url) }}"
                                         alt="profile-picture" />
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-black">
                                    {{ $customer->full_name }}
                                </p>
                                <p class="text-xs text-primary-grey">
                                    {{ $customer->email }}
                                </p>
                            </div>
                        </div>

                        <div class="text-sm text-black space-y-1">
                            <p><span class="text-primary-grey">Phone:</span> {{ $customer->phone ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <x-button href="{{ route('document.show', $customer->id) }}" size="full" variant="rest" >
                            <x-heroicon name="document-magnifying-glass" />
                            View
                        </x-button>

                        <x-button href="{{ route('document.create', ['customer' => $customer->id]) }}" size="full">
                            <x-heroicon name="document-plus"/>
                            New Document
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>