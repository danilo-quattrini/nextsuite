@props([
    'label' => 'Text Area',
    'name' => 'text-area'
])
<div class="my-5">
    <x-form.label-container label="{{ $label }}"/>
    <textarea {{$attributes->except(['class', 'name'])}} class="textarea-container" rows="5" cols="33"></textarea>
    <x-input-error for="{{ $name }}"/>
</div>