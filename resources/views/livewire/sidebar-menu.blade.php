<div class="h-full flex flex-col px-6.25 py-9.5 gap-8">
    {{-- Logo --}}
    <div class="flex items-center gap-4">
        <img src="{{ asset('img/nextsuite-logo.png') }}" class="h-8 w-8" alt="NextSuite Logo">
        <span class="text-3xl font-semibold">NextSuite</span>
    </div>

    {{-- Company --}}
    <div class="space-y-2">
        <p class="text-base text-primary-grey font-medium">Welcome</p>
        <p class="text-3xl font-bold">{{ Auth::user()->company->name }}</p>
    </div>


    {{-- OVERVIEW --}}
    <nav class="space-y-[8px]">

        <p class="text-base text-primary-grey font-medium mb-6.25">OVERVIEW</p>

        {{-- DASHBOARD --}}
        <x-sidebar-link :active="request()->routeIs('dashboard')" href="{{ route('dashboard') }}" >
            <x-slot:icon>
                <x-heroicon name="home" size="lg"/>
            </x-slot:icon>
            Home
        </x-sidebar-link>

        {{-- CUSTOMERS --}}
        <x-sidebar-link :active="request()->routeIs('customer.create')" href="{{ route('customer.create') }}" >
            <x-slot:icon>
                <x-heroicon name="users" size="lg"/>
            </x-slot:icon>
            Customers
        </x-sidebar-link>

        {{-- DOCUMENTS --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="document" size="lg"/>
            </x-slot:icon>
            Documents
        </x-sidebar-link>

        {{-- REPORTS --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="chart-pie" size="lg"/>
            </x-slot:icon>
            Reports
        </x-sidebar-link>

    </nav>

    {{-- MANAGEMENT --}}
    <nav class="space-y-[8px]">

    <p class="text-base text-primary-grey font-medium">MANAGEMENT</p>

        {{-- DASHBOARD --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="briefcase" size="lg"/>
            </x-slot:icon>
            Service
        </x-sidebar-link>

        {{-- EMPLOYEES --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="user-group" size="lg"/>
            </x-slot:icon>
            Employees
        </x-sidebar-link>
    </nav>

    {{-- TOOLS --}}
    <nav class="space-y-[8px]">

        <p class="text-base text-primary-grey font-medium">TOOLS</p>

        {{-- TEMPLATES --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="clipboard-document" size="lg"/>
            </x-slot:icon>
            Templates
        </x-sidebar-link>

    </nav>

    {{-- RESOURCES --}}
    <nav class="space-y-[8px]">

        <p class="text-base text-primary-grey font-medium">RESOURCES</p>

        {{-- BASE KNOWLEDGE --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="information-circle" size="lg"/>
            </x-slot:icon>
            Base Knowledge
        </x-sidebar-link>

        {{-- POLICIES --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="lifebuoy" size="lg"/>
            </x-slot:icon>
            Policies
        </x-sidebar-link>

        {{-- SUPPORT --}}
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="chat-bubble-oval-left-ellipsis" size="lg"/>
            </x-slot:icon>
            Support
        </x-sidebar-link>

    </nav>


    <div class="mt-auto pt-6 border-t">
        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="question-mark-circle" size="lg"/>
            </x-slot:icon>
            Help
        </x-sidebar-link>

        <x-sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="clock" size="lg"/>
            </x-slot:icon>
            Changelog
        </x-sidebar-link>

    </div>
</div>