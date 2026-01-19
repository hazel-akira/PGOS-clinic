# Tailwind CSS Configuration Guide

## Overview

Tailwind CSS has been customized for the School Clinic Management Portal with healthcare-appropriate styling, colors, and components.

## Color Palette

### Primary Colors
- **Primary Blue** (`primary-500: #007dff`) - Main brand color for healthcare interface
- Shades from `primary-50` (lightest) to `primary-900` (darkest)

### Medical Status Colors
- **Success** (`medical-success: #10b981`) - Green for healthy/cleared status
- **Warning** (`medical-warning: #f59e0b`) - Amber for caution
- **Danger** (`medical-danger: #ef4444`) - Red for urgent/critical
- **Info** (`medical-info: #3b82f6`) - Blue for information

### Triage Colors
- **Immediate** (`medical-triage-immediate: #dc2626`) - Red - requires immediate care
- **Urgent** (`medical-triage-urgent: #f59e0b`) - Amber - urgent but not immediate
- **Standard** (`medical-triage-standard: #3b82f6`) - Blue - standard care
- **Non-urgent** (`medical-triage-nonurgent: #10b981`) - Green - non-urgent

### Clinic Colors
- **Background** (`clinic-background: #f8fafc`) - Light gray background
- **Surface** (`clinic-surface: #ffffff`) - White card/surface color
- **Border** (`clinic-border: #e2e8f0`) - Light border color
- **Text Primary** (`clinic-text-primary: #1e293b`) - Dark text
- **Text Secondary** (`clinic-text-secondary: #64748b`) - Medium gray text
- **Text Muted** (`clinic-text-muted: #94a3b8`) - Light gray text

## Pre-built Components

### Status Badges
```html
<span class="badge-success">Cleared</span>
<span class="badge-warning">Caution</span>
<span class="badge-danger">Critical</span>
<span class="badge-info">Information</span>
```

### Triage Badges
```html
<span class="triage-immediate">Immediate</span>
<span class="triage-urgent">Urgent</span>
<span class="triage-standard">Standard</span>
<span class="triage-nonurgent">Non-urgent</span>
```

### Clinic Cards
```html
<div class="clinic-card">
    <!-- Card content -->
</div>
```

### Patient Info Cards
```html
<div class="patient-info-card">
    <!-- Patient information with gradient background -->
</div>
```

### Form Sections
```html
<div class="form-section">
    <h3 class="form-section-title">Section Title</h3>
    <!-- Form fields -->
</div>
```

### Medical Alerts
```html
<div class="alert-medical-success">
    Patient cleared for return to class
</div>

<div class="alert-medical-warning">
    Follow-up required
</div>

<div class="alert-medical-danger">
    Urgent medical attention needed
</div>
```

### Buttons
```html
<button class="btn-clinic-primary">Primary Action</button>
<button class="btn-clinic-secondary">Secondary Action</button>
```

### Medical Tables
```html
<table class="medical-table">
    <thead>
        <tr>
            <th>Patient Name</th>
            <th>Visit Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>2026-01-19</td>
            <td><span class="badge-success">Cleared</span></td>
        </tr>
    </tbody>
</table>
```

## Utility Classes

### Privacy Utilities
```html
<!-- Blur sensitive data, reveal on hover -->
<div class="blur-sensitive">
    Sensitive medical information
</div>
```

### Print Utilities
```html
<!-- Hide element when printing -->
<div class="no-print">Navigation</div>

<!-- Force page break when printing -->
<div class="print-break">New page content</div>
```

## Custom Animations

- `animate-pulse-slow` - Slow pulsing animation (3s)
- `animate-fade-in` - Fade in animation
- `animate-slide-up` - Slide up from bottom

## Building Assets

### Development (with Node.js 20+)
```bash
npm run dev
```

### Production Build
```bash
npm run build
```

### Current Workaround (Node.js < 20)
The application uses CDN fallbacks when Vite manifest is missing. For full Tailwind customization, upgrade Node.js and build assets.

## Customization

### Adding New Colors
Edit `tailwind.config.js`:
```js
colors: {
    yourColor: {
        500: '#your-hex-color',
        // ... other shades
    }
}
```

### Adding New Components
Add to `resources/css/app.css` in the `@layer components` section:
```css
.your-component {
    @apply /* Tailwind utilities */;
}
```

### Content Paths
Tailwind scans these paths for classes:
- Blade views: `./resources/views/**/*.blade.php`
- Filament resources: `./app/Filament/**/*.php`
- Laravel pagination: `./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php`

## Best Practices

1. **Use semantic color names** - Use `medical-success` instead of `green-500` for medical contexts
2. **Leverage pre-built components** - Use clinic-card, badge classes, etc.
3. **Maintain consistency** - Stick to the defined color palette
4. **Privacy first** - Use `blur-sensitive` for sensitive data
5. **Print-friendly** - Use `no-print` for navigation, `print-break` for page breaks

## Examples

### Patient Visit Card
```html
<div class="clinic-card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-clinic-text-primary">Visit Details</h3>
        <span class="triage-standard">Standard</span>
    </div>
    <div class="space-y-2">
        <p class="text-clinic-text-secondary">Patient: John Doe</p>
        <p class="text-clinic-text-secondary">Date: 2026-01-19</p>
        <p class="text-clinic-text-primary">Temperature: 98.6°F</p>
    </div>
    <div class="mt-4">
        <span class="badge-success">Cleared</span>
    </div>
</div>
```

### Medical Form
```html
<div class="form-section">
    <h3 class="form-section-title">Vital Signs</h3>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-clinic-text-primary mb-1">
                Temperature
            </label>
            <input type="number" class="w-full" placeholder="98.6">
        </div>
        <div>
            <label class="block text-sm font-medium text-clinic-text-primary mb-1">
                Blood Pressure
            </label>
            <input type="text" class="w-full" placeholder="120/80">
        </div>
    </div>
</div>
```
