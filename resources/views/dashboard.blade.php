<x-app-layout>
    <div class="flex gap-5 items-center justify-center my-12">
        <x-button href="{{ route('customer.create') }}" size="large">
            Create Customer
        </x-button>
    </div>
    <x-table :customers="$customers" />
</x-app-layout>
