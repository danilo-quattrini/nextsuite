@php use Illuminate\Pagination\LengthAwarePaginator; @endphp
<div>
    <div class="hidden md:block table-container">
        <table class="min-w-full w-full leading-normal">
            <thead>
            <tr>
                @foreach($columns as $column)
                    @if($isColumnVisible($column))
                        <th class="table-head__container {{ $isHiddenOnMobile($column) ? 'hidden md:table-cell' : '' }}">
                            <div class="table-head__content">
                                @if(isset($column['icon']))
                                    <x-heroicon
                                            :name="$column['icon']"
                                            size="lg"
                                    />
                                @endif
                                <span>{{ $column['label'] }}</span>
                            </div>
                        </th>
                    @endif
                @endforeach

                @if(count($actions) > 0)
                    <th class="table-head__container">
                        <div class="table-head__content">
                            <x-heroicon name="ellipsis-horizontal" size="sm"/>
                            <span>Actions</span>
                        </div>
                    </th>
                @endif
            </tr>
            </thead>
            <tbody class="divide-y divide-outline-grey">
            @forelse($tableData as $row)
                <tr class="bg-white hover:bg-outline-grey/60 transition">
                    @foreach($columns as $column)
                        @if($isColumnVisible($column))
                            <td class="p-6 bg-white {{ $isHiddenOnMobile($column) ? 'hidden md:table-cell' : '' }}">
                                @if($loop->first && $photoField && $nameField)
                                    {{-- First column with profile photo --}}
                                    <div class="flex items-center gap-6">
                                        <x-profile-image
                                                :src="$row->{$photoField}"
                                                :name="$row->{$nameField}"
                                                directory="{{ $resourceType }}-profile-photos"
                                                size="custom"
                                                class="w-10 h-10"
                                        />
                                        <p class="table-text">
                                            {!! $formatValue($getColumnValue($row, $column), $column) !!}
                                        </p>
                                    </div>
                                @elseif(($column['type'] ?? '') === 'checkbox')
                                    <x-toggle-container>
                                        <x-slot:element>
                                            <x-form.checkbox
                                                    :id="'checkbox-' . $row->{$primaryKey}"
                                                    :name="'checkbox-' . $row->{$primaryKey}"
                                                    wire:model.live="selectedRows.{{ $row->{$primaryKey} }}"
                                                    size="md"
                                                    :wrap="true"
                                            />
                                        </x-slot:element>
                                        <x-slot:span></x-slot:span>
                                    </x-toggle-container>
                                @else
                                    {{-- Regular column --}}
                                    <p class="table-text">
                                        {!! $formatValue($getColumnValue($row, $column), $column) !!}
                                    </p>
                                @endif
                            </td>
                        @endif
                    @endforeach

                    @if(count($actions) > 0)
                        <td class="relative p-6 bg-white">
                            <x-form.dropdown-button align="right">
                                <x-slot:trigger>
                                    <x-button
                                            type="button"
                                            variant="white"
                                            aria-label="{{ ucfirst($resourceType) }} actions"
                                    >
                                        <x-heroicon name="ellipsis-vertical" />
                                    </x-button>
                                </x-slot:trigger>

                                <x-slot:content>
                                    <div class="flex-col items-center space-y-3">
                                        <div class="flex flex-col space-y-2 min-w-40">
                                            @foreach($actions as $action)
                                                @if($canPerformAction($action, $row))
                                                    @php
                                                        $route = $getActionRoute($action, $row);
                                                        $colorClass = match($action['color'] ?? 'default') {
                                                            'danger', 'error' => 'text-secondary-error hover:bg-secondary-error-100',
                                                            'warning' => 'text-secondary-warning hover:bg-secondary-warning-100',
                                                            'success' => 'text-secondary-success hover:bg-secondary-success-100',
                                                            'primary' => 'text-primary hover:bg-secondary',
                                                            default => 'text-primary-grey hover:bg-outline-grey',
                                                        };
                                                    @endphp

                                                    @if($route)
                                                        {{-- Link action --}}
                                                        <a
                                                                href="{{ $route }}"
                                                                class="flex items-center gap-2 px-3 py-2 rounded-md text-sm transition {{ $colorClass }}"
                                                        >
                                                            @if(isset($action['icon']))
                                                                <x-heroicon :name="$action['icon']" />
                                                            @endif
                                                            <span>{{ $action['label'] }}</span>
                                                        </a>
                                                    @elseif(isset($action['event']))
                                                        {{-- Event action --}}
                                                        <button
                                                                type="button"
                                                                wire:click.prevent="$dispatch('{{ $action['event'] }}', { id: {{ $row->{$primaryKey} }}, type: '{{ $resourceType }}' })"
                                                                class="flex items-center gap-2 px-3 py-2 rounded-md text-sm cursor-pointer transition {{ $colorClass }}"
                                                        >
                                                            @if(isset($action['icon']))
                                                                <x-heroicon :name="$action['icon']" />
                                                            @endif
                                                            <span>{{ $action['label'] }}</span>
                                                        </button>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </x-slot:content>
                            </x-form.dropdown-button>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $getVisibleColumnsCount() }}" class="p-8 text-center bg-white">
                        <x-empty-state
                                icon="inbox"
                                message="No {{ $resourceType }} it's availabe or have been found"
                                description="{{ $emptyMessage }}"
                        />
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>