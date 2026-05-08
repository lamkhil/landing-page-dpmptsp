@props(['color' => 'rgb(14 77 164 / 0.15)', 'size' => 24])
{{-- Dot grid pattern — used as background ornament. Pure inline SVG, no asset. --}}
<svg {{ $attributes->merge(['class' => 'absolute pointer-events-none select-none']) }} aria-hidden="true">
    <defs>
        <pattern id="decor-dots-{{ md5($color) }}" x="0" y="0" width="{{ $size }}" height="{{ $size }}" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="1.4" fill="{{ $color }}"/>
        </pattern>
    </defs>
    <rect width="100%" height="100%" fill="url(#decor-dots-{{ md5($color) }})"/>
</svg>
