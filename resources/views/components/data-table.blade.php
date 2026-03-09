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
                                @else
                                    {{-- Regular column --}}
                                    <p class="table-text">
                                        {!! $formatValue($getColumnValue($row, $column), $column) !!}
                                    </p>
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $getVisibleColumnsCount() }}" class="p-8 text-center bg-white">
                        <x-empty-state
                                icon="inbox"
                                message="No customer available"
                                description="sorry but at this moment there is no customer here"
                        />
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($tableData instanceof LengthAwarePaginator)
        <div class="page-content__pagination">
            {{ $tableData->links('components.pagination') }}
        </div>
    @endif
</div>