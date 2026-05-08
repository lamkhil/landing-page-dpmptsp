@props(['color' => 'rgb(255 255 255 / 0.06)'])
{{-- Diagonal grid-line accent — used inside dark hero/CTA sections. --}}
<svg {{ $attributes->merge(['class' => 'absolute inset-0 pointer-events-none select-none']) }} aria-hidden="true">
    <defs>
        <pattern id="decor-grid-{{ md5($color) }}" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="{{ $color }}" stroke-width="1"/>
        </pattern>
    </defs>
    <rect width="100%" height="100%" fill="url(#decor-grid-{{ md5($color) }})"/>
</svg>
