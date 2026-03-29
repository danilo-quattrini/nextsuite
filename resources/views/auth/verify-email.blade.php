<x-guest-layout>
    <div class="min-h-screen mx-auto flex flex-col justify-center items-center overflow-hidden">
        <x-card.card-container card-size="lg">

            {{-- Logo --}}
            <div class="flex justify-center items-center mb-6">
                <x-authentication-card-logo />
            </div>

            {{-- Icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center">
                    <x-heroicon name="envelope" class="text-primary"  size="xl"/>
                </div>
            </div>

            {{-- Heading --}}
            <div class="text-center mb-2">
                <h3>
                    {{ __('Verify your email') }}
                </h3>
            </div>

            {{-- Description --}}
            <div class="text-center mb-6 px-4">
                <p class="text-sm text-primary-grey leading-relaxed">
                    {{ __("We've sent a verification link to your email address. Please check your inbox and click the link to continue.") }}
                </p>
                <p class="text-sm text-primary-grey-400 mt-1">
                    {{ __("Didn't receive it? Check your spam folder or request a new one below.") }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-3 w-full px-4">

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-button size="full" type="submit">
                        {{ __('Resend Verification Email') }}
                    </x-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button size="full" type="submit" variant="rest">
                        {{ __('Log Out') }}
                    </x-button>
                </form>

            </div>

            {{-- Edit Profile --}}
            <div class="mt-5 text-center">
                <a href="{{ route('profile.show') }}">
                    <x-span-link>{{ __('Edit Profile') }}</x-span-link>
                </a>
            </div>

        </x-card.card-container>
    </div>
</x-guest-layout>