<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>
        <x-slot:message>
        </x-slot:message>
        {!! $policy !!}
    </x-authentication-card>
</x-guest-layout>
