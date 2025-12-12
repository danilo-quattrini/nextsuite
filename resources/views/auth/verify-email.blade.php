<x-guest-layout>
    <x-authentication-card>
        <x-slot:logo>
            <x-authentication-card-logo />
        </x-slot:logo>

        <x-slot:message>
            {{ __('Before continuing, could you verify your email address?.') }}<br>
            {{ __('We sent you a link! If you didn\'t receive thought email, we will gladly send you another') }}
        </x-slot:message>


        <div class="mt-2 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div class="w-[470px] flex items-center justify-center">
                    <x-button size="large" type="submit">
                        {{ __('Resend Email') }}
                    </x-button>
                </div>
            </form>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf

                <div class="w-[470px] flex items-center justify-center">
                    <x-button size="large" type="submit" variant="rest">
                        {{ __('Log Out') }}
                    </x-button>
                </div>
            </form>
            <div>
                <a href="{{ route('profile.show') }}">
                    <x-span-link>{{ __('Edit Profile') }}</x-span-link>
                </a>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
