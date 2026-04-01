{{--
    AVATAR UPLOAD COMPONENT
    =======================
    A styled circular photo uploader with live preview, drag-over state,
    and an overlay on hover. Progressively replaces the placeholder icon
    with the selected image without a page reload.

    USAGE — basic:
        <x-form.avatar-upload name="profile_photo" />

    USAGE — with existing photo (edit profile page):
        <x-form.avatar-upload
            name="profile_photo"
            :current="$user->profile_photo_url"
        />

    USAGE — custom label and size:
        <x-form.avatar-upload
            name="profile_photo"
            label="Upload company logo"
            size="lg"
        />

    PROPS
    -----
    name       string   Input name attribute (default: 'profile_photo')
    id         string   Input/label id pair (defaults to name value)
    current    string   URL of existing image to pre-populate the preview
    label      string   Helper text below the avatar (default: 'Upload photo')
    size       string   sm | md | lg  — controls avatar diameter (default: md)
    error      bool     Highlights the ring in error color (default: false)

    All extra attributes are forwarded to the hidden <input type="file">.

    NOTES
    -----
    - Preview is handled entirely client-side via Alpine + FileReader.
    - Drag-and-drop onto the circle is supported.
    - Accepts image/* by default; override with :accept="'image/png,image/jpeg'".
    - Wire/Livewire: pass wire:model on the component and it forwards to <input>.
--}}

@props([
    'name'    => 'profile_photo',
    'id'      => null,
    'current' => null,
    'label'   => null,
    'size'    => 'md',
    'error'   => false,
])

@php
    $inputId = $id ?? $name;

    $allowedSizes = ['sm', 'md', 'lg'];
    $size = in_array($size, $allowedSizes, true) ? $size : 'md';

    $wrapperClass = trim(implode(' ', array_filter([
        'avatar-upload',
        "avatar-upload--{$size}",
        $error ? 'has-error' : '',
    ])));

    $labelText = $label ?? __('Upload photo');
@endphp

<div
        class="{{ $wrapperClass }}"
        x-data="{
        preview: @js($current),
        dragging: false,

        handleFile(file) {
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => this.preview = e.target.result;
            reader.readAsDataURL(file);
        },

        onFileInput(e) {
            this.handleFile(e.target.files[0]);
        },

        onDrop(e) {
            this.dragging = false;
            this.handleFile(e.dataTransfer.files[0]);
            {{-- Sync the file to the hidden input so form submission works --}}
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            $refs.fileInput.files = dt.files;
        }
    }"
>
    {{-- Clickable / droppable circle --}}
    <label
            for="{{ $inputId }}"
            class="avatar-upload__circle"
            :class="{
            'is-dragging': dragging,
            'has-preview': preview
        }"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="onDrop($event)"
            aria-label="{{ $labelText }}"
    >
        {{-- Existing / preview image --}}
        <img
                x-show="preview"
                :src="preview"
                class="avatar-upload__preview"
                alt="{{ __('Profile photo preview') }}"
        />

        {{-- Placeholder icon (shown when no preview) --}}
        <span class="avatar-upload__placeholder" x-show="!preview">
            <x-heroicon name="user-plus" size="{{ $size }}" />
        </span>

        {{-- Hover overlay (shown over existing image) --}}
        <span class="avatar-upload__overlay" x-show="preview">
            <x-heroicon name="camera" size="{{ $size }}" />
        </span>
    </label>

    {{-- Helper label --}}
    <span class="avatar-upload__label">{{ $labelText }}</span>

    {{-- Hidden file input --}}
    <input
            x-ref="fileInput"
            id="{{ $inputId }}"
            name="{{ $name }}"
            type="file"
            accept="image/*"
            class="sr-only"
            @change="onFileInput($event)"
            {{ $attributes->except(['class', 'id', 'name', 'type', 'accept']) }}
    />

    {{-- Validation error --}}
    <x-input-error :for="$name" />
</div>