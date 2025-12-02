<x-guest-layout>
    <div>
        <!-- Typography -->
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
        <p class="text-xxs font-bold tracking-button">Button XXS</p>
    </div>

    <div>
        <!-- Buttons -->
        <x-button variant="rest">Button</x-button>
        <x-button variant="primary">Button</x-button>
        <x-button href="/login" variant="disable">Login</x-button>
        <x-button href="/register" variant="warning">
            Register
        </x-button>
        <x-button variant="error">Button</x-button>
        <!-- Icon Buttons -->
        <x-button variant="primary">
            <x-heroicon name="user" variant="outline" size="md"/>
            Button
        </x-button>
        <x-button variant="rest">
            <x-heroicon name="pencil" variant="outline" size="md"/>
            Button
        </x-button>
        <x-button variant="error">
            <x-heroicon name="trash" variant="outline" size="md"/>
            Button
        </x-button>
        <x-button variant="warning">
            <x-heroicon name="exclamation-triangle" variant="outline" size="md"/>
            Button
        </x-button>

        <!-- Outline Buttons-->
        <x-button variant="outline-primary">Button</x-button>
        <x-button variant="outline-disable">Button</x-button>
        <x-button variant="outline-error">Button</x-button>
        <x-button variant="outline-warning">Button</x-button>

        <!-- Icon Buttons-->
        <x-button variant="primary">
            <x-heroicon name="user" variant="outline" size="md"/>
        </x-button>
        <x-button variant="rest">
            <x-heroicon name="pencil" variant="outline" size="md"/>
        </x-button>
        <x-button variant="error">
            <x-heroicon name="trash" variant="outline" size="md"/>
        </x-button>
        <x-button variant="warning">
            <x-heroicon name="exclamation-triangle" variant="outline" size="md"/>
        </x-button>
    </div>

    <div>
        <x-form.input-container :recovery_link="true"/>
    </div>
</x-guest-layout>