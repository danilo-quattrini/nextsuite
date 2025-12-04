@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-secondary-error dark:text-secondary-error']) }}>{{ $message }}</p>
@enderror
