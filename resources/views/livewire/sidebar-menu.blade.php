@php
    $sidebarSections = [
        [
            'title' => 'Overview',
            'items' => [
                [
                    'type' => 'link',
                    'label' => 'Home',
                    'icon' => 'home',
                    'href' => route('dashboard'),
                    'active' => request()->routeIs('dashboard'),
                ],
                [
                    'type' => 'dropdown',
                    'label' => 'Customers',
                    'icon' => 'users',
                    'active' => request()->routeIs('customer.*'),
                    'children' => [
                        [
                            'label' => 'Add customer',
                            'icon' => 'user-plus',
                            'href' => route('customer.create'),
                            'active' => request()->routeIs('customer.create'),
                        ],
                        [
                            'label' => 'Customer list',
                            'icon' => 'list-bullet',
                            'href' => route('customer.list'),
                            'active' => request()->routeIs('customer.list'),
                        ],
                    ],
                ],
                [
                    'type' => 'dropdown',
                    'label' => 'Documents',
                    'icon' => 'document',
                    'active' => request()->routeIs(['document.*', 'template.*']),
                    'children' => [
                        [
                            'label' => 'Generate',
                            'icon' => 'document-plus',
                            'href' => route('document.index'),
                            'active' => request()->routeIs('document.index'),
                        ],
                        [
                            'label' => 'Templates',
                            'icon' => 'clipboard-document',
                            'href' => route('template.index'),
                            'active' => request()->routeIs('template.index'),
                        ],
                    ],
                ],
                [
                    'type' => 'link',
                    'label' => 'Report',
                    'icon' => 'chart-pie',
                    'href' => route('report.index'),
                    'active' => request()->routeIs('report.index'),
                ],
            ],
        ],
        [
            'title' => 'Management',
            'items' => [
                [
                    'type' => 'dropdown',
                    'label' => 'Company',
                    'icon' => 'building-office',
                    'active' => request()->routeIs('company.*'),
                    'children' => [
                        [
                            'label' => 'Create Company',
                            'icon' => 'plus',
                            'href' => route('company.create'),
                            'active' => request()->routeIs('company.create'),
                        ],
                        [
                            'label' => 'Details',
                            'icon' => 'information-circle',
                            'href' => route('company.show'),
                            'active' => request()->routeIs('company.show'),
                        ],
                    ],
                ],
                [
                    'type' => 'link',
                    'label' => 'Service',
                    'icon' => 'briefcase',
                    'href' => '#',
                    'active' => false,
                ],
                [
                    'type' => 'link',
                    'label' => 'Employees',
                    'icon' => 'user-group',
                    'href' => '#',
                    'active' => false,
                ],
                [
                    'type' => 'link',
                    'label' => 'Skills',
                    'icon' => 'star',
                    'href' => route('skill.create'),
                    'active' => request()->routeIs('skill.create'),
                ],
            ],
        ],
        [
            'title' => 'Resources',
            'items' => [
                [
                    'type' => 'link',
                    'label' => 'Base Knowledge',
                    'icon' => 'information-circle',
                    'href' => '#',
                    'active' => false,
                ],
                [
                    'type' => 'link',
                    'label' => 'Policies',
                    'icon' => 'lifebuoy',
                    'href' => '#',
                    'active' => false,
                ],
                [
                    'type' => 'link',
                    'label' => 'Support',
                    'icon' => 'chat-bubble-oval-left-ellipsis',
                    'href' => '#',
                    'active' => false,
                ],
            ],
        ],
    ];

    $sidebarFooter = [
        [
            'label' => 'Help',
            'icon' => 'question-mark-circle',
            'href' => '#',
            'active' => false,
        ],
        [
            'label' => 'Changelog',
            'icon' => 'clock',
            'href' => route('changelog'),
            'active' => request()->routeIs('changelog'),
        ],
    ];
@endphp


<div class="sidebar">
    <div class="sidebar__header">
        <div class="sidebar__brand">
            <img src="{{ asset('img/nextsuite-logo.png') }}" class="sidebar__logo" alt="NextSuite Logo">
            <span class="sidebar__brand-name" x-show="!collapsed" x-transition.opacity.duration.50ms>NextSuite</span>

            <button @click="collapsed = !collapsed" class="sidebar__toggle">
                <x-heroicon name="bars-4" size="md" x-show="!collapsed"/>
                <x-heroicon name="bars-4" size="md" x-show="collapsed"/>
            </button>
        </div>

        <div class="sidebar__company" x-show="!collapsed" x-collapse.duration.50ms>
            <p class="sidebar__welcome">Welcome</p>
            <p class="sidebar__company-name">
                {{ Auth::user()->company->name ?? Auth::user()->full_name }}
            </p>
        </div>
    </div>

    @foreach($sidebarSections as $section)
        <nav class="sidebar__section">
            <p class="sidebar__section-title" x-show="!collapsed" x-transition.opacity.duration.50ms>{{ strtoupper($section['title']) }}</p>

            <div class="sidebar__section-items">
                @foreach($section['items'] as $item)
                    @if($item['type'] === 'dropdown')
                        <x-sidebar.dropdown-link
                            :active="$item['active']"
                            x-tooltip.placement.right="collapsed ? '{{ $item['label'] }}' : ''"
                        >
                            <x-slot:icon>
                                <x-heroicon :name="$item['icon']" size="md"/>
                            </x-slot:icon>
                            {{ $item['label'] }}

                            <x-slot:elements>
                                @foreach($item['children'] as $child)
                                    <x-sidebar.sidebar-link
                                        href="{{ $child['href'] }}"
                                        :active="$child['active']"
                                    >
                                        <x-slot:icon>
                                            <x-heroicon :name="$child['icon']" size="md"/>
                                        </x-slot:icon>
                                        {{ $child['label'] }}
                                    </x-sidebar.sidebar-link>
                                @endforeach
                            </x-slot:elements>
                        </x-sidebar.dropdown-link>

                    @else
                        <x-sidebar.sidebar-link
                                href="{{ $item['href'] }}"
                                :active="$item['active']"
                                x-tooltip.placement.right="collapsed ? '{{ $item['label'] }}' : ''"
                        >
                            <x-slot:icon>
                                <x-heroicon :name="$item['icon']" size="md"/>
                            </x-slot:icon>
                            {{ $item['label'] }}
                        </x-sidebar.sidebar-link>
                    @endif
                @endforeach
            </div>
        </nav>
    @endforeach

    <div class="sidebar__footer">
        @foreach($sidebarFooter as $item)
            <x-sidebar.sidebar-link
                href="{{ $item['href'] }}"
                :active="$item['active']"
                x-tooltip.placement.right="collapsed ? '{{ $item['label'] }}' : ''"
            >
                <x-slot:icon>
                    <x-heroicon :name="$item['icon']" size="md"/>
                </x-slot:icon>
                {{ $item['label'] }}
            </x-sidebar.sidebar-link>
        @endforeach
    </div>
</div>
