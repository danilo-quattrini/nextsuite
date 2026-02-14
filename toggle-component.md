# Toggle Switch Component

A modern, accessible toggle switch component for Laravel Blade with Tailwind CSS.

## Installation

### 1. Add the Blade Component

Save `toggle-switch.blade.php` to your components directory:
```
resources/views/components/form/toggle-switch.blade.php
```

### 2. Add the CSS

Add `toggle-switch.css` to your main CSS file or import it:

**Option A: Import in app.css**
```css
@import './components/toggle-switch.css';
```

**Option B: Add directly to app.css**
Copy the contents of `toggle-switch.css` into your `resources/css/app.css` file.

### 3. Compile Assets

```bash
npm run dev
```

## Basic Usage

```blade
{{-- Simple toggle --}}
<x-form.toggle-switch label="Enable notifications" />

{{-- Pre-checked --}}
<x-form.toggle-switch label="Active" checked />

{{-- With Livewire --}}
<x-form.toggle-switch 
    label="Category Active"
    wire:model.live="selectedCategory"
    value="1"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `size` | string | `'md'` | Size variant: `'sm'`, `'md'`, `'lg'` |
| `label` | string | `null` | Label text |
| `description` | string | `null` | Description text below label |
| `checked` | boolean | `false` | Initial checked state |
| `disabled` | boolean | `false` | Disabled state |

## Examples

### Sizes

```blade
<x-form.toggle-switch size="sm" label="Small" />
<x-form.toggle-switch size="md" label="Medium" />
<x-form.toggle-switch size="lg" label="Large" />
```

### With Description

```blade
<x-form.toggle-switch 
    label="Email notifications" 
    description="Receive updates via email"
/>
```

### Livewire Integration

```blade
{{-- Boolean binding --}}
<x-form.toggle-switch 
    label="Feature Enabled"
    wire:model="isEnabled"
/>

{{-- Array binding (like checkboxes) --}}
<x-form.toggle-switch 
    label="Category 1"
    wire:model.live="selectedCategories"
    value="1"
/>
```

### Disabled States

```blade
<x-form.toggle-switch label="Disabled" disabled />
<x-form.toggle-switch label="Disabled & Checked" checked disabled />
```

### With Additional Attributes

```blade
<x-form.toggle-switch 
    label="Accept terms"
    name="terms"
    id="terms-toggle"
    required
/>
```

## Customization

### Changing Colors

Edit the CSS file to customize colors:

```css
/* Track background when unchecked */
.toggle-switch__track {
    @apply bg-gray-300;
    @apply peer-checked:bg-green-500; /* Change to your color */
}

/* Focus ring */
.toggle-switch__track {
    @apply peer-focus-visible:ring-green-500/25; /* Change to your color */
}
```

### Custom Sizes

Add new size variants in the CSS:

```css
.toggle-switch--xl {
    --toggle-width: 4rem;
    --toggle-height: 2rem;
    --toggle-thumb-size: 1.75rem;
    --toggle-spacing: 1rem;
}
```

Then use it:
```blade
<x-form.toggle-switch size="xl" label="Extra Large" />
```

### Adjusting Spacing

Modify CSS variables for fine-tuning:

```css
.toggle-switch--md {
    --toggle-width: 2.75rem;      /* Track width */
    --toggle-height: 1.5rem;      /* Track height */
    --toggle-thumb-size: 1.25rem; /* Thumb size */
    --toggle-spacing: 0.75rem;    /* Gap between toggle and label */
}
```

## Accessibility

The component includes:
- ✅ `role="switch"` for screen readers
- ✅ Keyboard focus indicators
- ✅ Proper label associations
- ✅ Disabled state handling

## Browser Support

Works in all modern browsers that support:
- CSS custom properties
- Tailwind CSS peer modifiers
- CSS `@apply` directive

## License

MIT
