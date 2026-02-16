@props([
    'description' => null
])
<label class="ds-checkbox-container">
    {{$element}}
    {{$span}}
    <span class="ds-checkbox-label">
        {{$slot}}
        <br>
        @if($description)
            <span class="ds-checkbox-description">{{$description}}</span>
        @endif
    </span>

</label>
