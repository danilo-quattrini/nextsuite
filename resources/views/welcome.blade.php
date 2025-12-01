<x-guest-layout>
    <!-- DESIGN SYSTEM EXAMPLE -->
    <h1> Heading 1 </h1>
    <h2> Heading 2 </h2>
    <h3> Heading 3 </h3>
    <h4> Heading 4 </h4>
    <h5> Heading 5 </h5>
    <h6>  Heading 6 </h6>
    <p >Subtitle M</p>
    <p>Subtitle S</p>
    <p class="text-lg font-medium">Body L</p>
    <p class="text-base font-medium">Body M</p>
    <p class="text-sm font-medium">Body S</p>
    <p class="text-xs font-medium">Body XS</p>
    <p class="text-xxs font-medium">Body XXS</p>
    <p class="text-lg font-bold">Button L</p>
    <p class="text-base font-bold tracking-button">Button M</p>
    <p class="text-sm font-bold tracking-button">Button S</p>
    <p class="text-xs font-bold tracking-button">Button XS</p>
    <p class="text-2xs font-bold tracking-button">Button XXS</p>

    <x-button variant="rest">Button</x-button>
    <x-button variant="primary">Button</x-button>
    <x-button href="/login" variant="disable">Login</x-button>

    <x-button variant="primary">
        <x-heroicon name="user" variant="outline" size="md"/>
        Icon Button
    </x-button>
</x-guest-layout>