<div class="sidebar">
    {{-- Logo --}}
    <div class="flex items-center gap-4">
        <img src="{{ asset('img/nextsuite-logo.png') }}" class="h-8 w-8" alt="NextSuite Logo">
        <span class="text-3xl font-semibold">NextSuite</span>
    </div>

    {{-- Company --}}
    <div class="space-y-2">
        <p class="text-base text-primary-grey font-medium">Welcome</p>
        <p class="text-3xl font-bold line-clamp-2 h-full">
            {{ Auth::user()->company->name ?? Auth::user()->full_name }}
        </p>
    </div>


    {{-- OVERVIEW --}}
    <nav>

        <p class="subheading" >OVERVIEW</p>

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

        {{-- DOCUMENTS DROPDOWN --}}
        <x-sidebar.dropdown-link :active="request()->routeIs('document.*')" >
            <x-slot:icon>
                <x-heroicon name="document" size="lg"/>
            </x-slot:icon>
            Documents

            <x-slot:elements>

                <x-sidebar.sidebar-link
                        href="{{ route('document.index') }}"
                        :active="request()->routeIs('document.index')"
                >
                    <x-slot:icon>
                        <x-heroicon name="document-plus" size="lg"/>
                    </x-slot:icon>
                    Generate
                </x-sidebar.sidebar-link>

                <x-sidebar.sidebar-link href="" >
                    <x-slot:icon>
                        <x-heroicon name="clipboard-document" size="lg"/>
                    </x-slot:icon>
                    Templates
                </x-sidebar.sidebar-link>
            </x-slot:elements>

        </x-sidebar.dropdown-link>

        {{-- REPORTS --}}
        <x-sidebar.sidebar-link href="" >
            <x-slot:icon>
                <x-heroicon name="chart-pie" size="lg"/>
            </x-slot:icon>
            Reports
        </x-sidebar.sidebar-link>

    </nav>

    {{-- MANAGEMENT --}}
    <nav>

    <p class="subheading" >MANAGEMENT</p>

        {{-- COMPANY DROPDOWN --}}
        <x-sidebar.dropdown-link :active="request()->routeIs('company.*')">
            <x-slot:icon>
                <x-heroicon name="building-office" size="lg"/>
            </x-slot:icon>
            Company
            <x-slot:elements>
                <x-sidebar.sidebar-link
                        href="{{ route('company.show') }}"
                        :active="request()->routeIs('company.show')"
                >
                    <x-slot:icon>
                        <x-heroicon name="information-circle" size="lg"/>
                    </x-slot:icon>
                    Details
                </x-sidebar.sidebar-link>
            </x-slot:elements>
        </x-sidebar.dropdown-link>

        {{-- SERVICES --}}
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

    {{-- RESOURCES --}}
    <nav>

        <p class="subheading" >RESOURCES</p>

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