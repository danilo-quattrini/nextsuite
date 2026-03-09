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
        </table>
    </div>
</div>