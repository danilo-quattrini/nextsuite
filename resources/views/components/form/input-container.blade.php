@props(['label' => 'Default Label', 'recovery_link' => 'false'])
<div class="w-96 inline-flex flex-col justify-start items-start gap-4">
    <!-- LABEL SECITION -->
    <div class="self-stretch inline-flex justify-between items-start text-sm font-medium">
        <x-label class="flex-1 justify-end leading-5">{{$label}}</x-label>
        @if($recovery_link)
            <x-label class="justify-end text-primary">{{$label}}</x-label>
        @endif
    </div>
    <!-- INPUT PART -->
    <x-input left-icon="user" right-icon="user" placeholder="Full Name"></x-input>
</div>