@props(['label' => 'Default Label'])
<!-- LABEL SECTION -->
<div class="w-full flex text-sm font-medium">
    <x-label class="flex-1 justify-start items-start leading-5">{{$label}}</x-label>
    <div class="justify-end items-end leading-5">
        {{$slot}}
    </div>
</div>
