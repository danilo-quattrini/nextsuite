@props([
    'src' => null,
    'name' => 'Unknown',
    'size' => 'default',
    'directory' => null,
])
@php
    $baseClass = 'rounded-full object-cover';
    $sizes = [
        'small' => 'w-24 h-24',
        'default' => 'w-40 h-40',
        'custom' => '',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['default'];
    $classes = trim($baseClass . ' ' . $sizeClass);

    $resolvedSrc = null;
    if (! empty($src)) {
        $srcValue = $src;
        $isAbsolute = is_string($srcValue)
            && (str_starts_with($srcValue, 'http://')
                || str_starts_with($srcValue, 'https://')
                || str_starts_with($srcValue, '/'));

        if ($directory && ! $isAbsolute) {
            $directory = trim($directory, '/');
            $resolvedSrc = asset('storage/' . $directory . '/' . ltrim($srcValue, '/'));
        } else {
            $resolvedSrc = $srcValue;
        }
    }

    if (! $resolvedSrc) {
        $fallbackName = $name ?: 'User';
        $resolvedSrc = 'https://ui-avatars.com/api/?name=' . urlencode($fallbackName) . '&color=5E81F4&background=5E81F440';
    }

    $altText = $attributes->get('alt', trim(($name ?: 'User') . ' profile image'));
@endphp
<img
        {{ $attributes->merge(['class' => $classes, 'alt' => $altText]) }}
        src="{{ $resolvedSrc }}"
/>
