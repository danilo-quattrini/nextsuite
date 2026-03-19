@props(['label' => 'Default Label', 'required' => false])
<!-- LABEL SECTION -->
<div class="w-full flex text-sm font-medium">
    <x-label class="flex-1 justify-start items-start leading-sm">{{$label}}@if($required === true)<span class="text-secondary-error ml-0.5">*</span>@endif</x-label>
    <div class="justify-end items-end leading-sm">
        {{$slot}}
    </div>
</div>
