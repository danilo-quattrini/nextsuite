<div class="h-full flex flex-col px-6.25 py-9.5 gap-8 overflow-y-auto">
    {{-- Logo --}}
    <div class="flex items-center gap-4">
        <img src="{{ asset('img/nextsuite-logo.png') }}" class="h-8 w-8" alt="NextSuite Logo">
        <span class="text-3xl font-semibold">NextSuite</span>
    </div>

    {{-- Company --}}
    <div class="space-y-2">
        <p class="text-base text-primary-grey font-medium">Welcome</p>
        <p class="text-3xl font-bold">{{ Auth::user()->company->name ?? Auth::user()->full_name }}</p>
    </div>


    {{-- OVERVIEW --}}
    <nav class="space-y-[8px]">

        <p class="text-base text-primary-grey font-medium mb-6.25">OVERVIEW</p>

        {{-- DASHBOARD --}}
        <x-sidebar.sidebar-link :active="request()->routeIs('dashboard')" href="{{ route('dashboard') }}" >
            <x-slot:icon>
                <x-heroicon name="home" size="lg"/>
            </x-slot:icon>
            Home
        </x-sidebar.sidebar-link>

        {{-- CUSTOMERS DROPDOWN--}}
        <x-sidebar.dropdown-link :active="request()->routeIs('customer.*')">
            <x-slot:icon>
                <x-heroicon name="users" size="lg"/>
            </x-slot:icon>
            Customers
            <x-slot:elements>
                <x-sidebar.sidebar-link
                        href="{{ route('customer.create') }}"
                        :active="request()->routeIs('customer.create')"
                >
                    <x-slot:icon>
                        <x-heroicon name="user-plus" size="lg"/>
                    </x-slot:icon>
                    Add customer
                </x-sidebar.sidebar-link>

                <x-sidebar.sidebar-link
                        href="{{ route('customer.list') }}"
                        :active="request()->routeIs('customer.list')"
                >
                    <x-slot:icon>
                        <x-heroicon name="list-bullet" size="lg"/>
                    </x-slot:icon>
                    Customer list
                </x-sidebar.sidebar-link>
            </x-slot:elements>
        </x-sidebar.dropdown-link>

        {{-- DOCUMENTS --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="document" size="lg"/>
            </x-slot:icon>
            Documents
        </x-sidebar.sidebar-link>

        {{-- REPORTS --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="chart-pie" size="lg"/>
            </x-slot:icon>
            Reports
        </x-sidebar.sidebar-link>

    </nav>

    {{-- MANAGEMENT --}}
    <nav class="space-y-[8px]">

    <p class="text-base text-primary-grey font-medium">MANAGEMENT</p>

        {{-- DASHBOARD --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="briefcase" size="lg"/>
            </x-slot:icon>
            Service
        </x-sidebar.sidebar-link>

        {{-- EMPLOYEES --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="user-group" size="lg"/>
            </x-slot:icon>
            Employees
        </x-sidebar.sidebar-link>
    </nav>

    {{-- TOOLS --}}
    <nav class="space-y-[8px]">

        <p class="text-base text-primary-grey font-medium">TOOLS</p>

        {{-- TEMPLATES --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="clipboard-document" size="lg"/>
            </x-slot:icon>
            Templates
        </x-sidebar.sidebar-link>

    </nav>

    {{-- RESOURCES --}}
    <nav class="space-y-[8px]">

        <p class="text-base text-primary-grey font-medium">RESOURCES</p>

        {{-- BASE KNOWLEDGE --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="information-circle" size="lg"/>
            </x-slot:icon>
            Base Knowledge
        </x-sidebar.sidebar-link>

        {{-- POLICIES --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="lifebuoy" size="lg"/>
            </x-slot:icon>
            Policies
        </x-sidebar.sidebar-link>

        {{-- SUPPORT --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="chat-bubble-oval-left-ellipsis" size="lg"/>
            </x-slot:icon>
            Support
        </x-sidebar.sidebar-link>

    </nav>


    <div class="mt-auto pt-6 border-t">
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="question-mark-circle" size="lg"/>
            </x-slot:icon>
            Help
        </x-sidebar.sidebar-link>

        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="clock" size="lg"/>
            </x-slot:icon>
            Changelog
        </x-sidebar.sidebar-link>

    </div>
</div>