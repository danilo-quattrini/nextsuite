@props(['label' => 'Default Label', 'required' => false])
<!-- LABEL SECTION -->
<div class="w-full flex text-sm font-medium">
    <x-label class="flex-1 justify-start items-start leading-5">{{$label}}@if($required === true)<span class="text-secondary-error ml-0.5">*</span>@endif</x-label>
    <div class="justify-end items-end leading-5">
        {{$slot}}
    </div>
</div>
