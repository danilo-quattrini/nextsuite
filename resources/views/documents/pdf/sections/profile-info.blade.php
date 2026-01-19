{{-- resources/views/documents/pdf/sections/personal-info.blade.php --}}
<div class="personal-info-section">
    <h2 class="section-title">{{ $section->title }}</h2>

    @if($section->config['layout'] === 'two_column')
        <div class="two-column">
            @php
                $fields = $section->config['fields'];
                $half = ceil(count($fields) / 2);
                $leftFields = array_slice($fields, 0, $half);
                $rightFields = array_slice($fields, $half);
            @endphp

            <div class="column">
                @foreach($leftFields as $field)
                    @if(isset($data[$field['key']]) && $data[$field['key']])
                        <div class="field-value">
                            <span class="field-label">{{ $field['label'] }}:</span>
                            {{ $data[$field['key']] }}
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="column">
                @foreach($rightFields as $field)
                    @if(isset($data[$field['key']]) && $data[$field['key']])
                        <div class="field-value">
                            <span class="field-label">{{ $field['label'] }}:</span>
                            {{ $data[$field['key']] }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        {{-- Single column layout --}}
        @foreach($section->config['fields'] as $field)
            @if(isset($data[$field['key']]) && $data[$field['key']])
                <div class="field-value">
                    <span class="field-label">{{ $field['label'] }}:</span>
                    {{ $data[$field['key']] }}
                </div>
            @endif
        @endforeach
    @endif
</div>