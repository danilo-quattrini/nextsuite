<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{asset('img/nextsuite-logo.png')}}" type="image/png">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="bg-light">
        <div class="font-sans text-black dark:text-white antialiased">
            {{ $slot }}
        </div>
        <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-4 w-[420px] max-w-[90vw]">
            @if (session('status'))
                <x-alert
                        type="success"
                        title="Success"
                        :message="session('status')"
                />
            @endif

            @if (session('warning'))
                <x-alert
                        type="warning"
                        title="Warning"
                        :message="session('warning')"
                />
            @endif

            @if (session('error'))
                <x-alert
                        type="error"
                        title="Error"
                        :message="session('error')"
                />
            @endif
        </div>
        @livewireScripts
    </body>
</html>
