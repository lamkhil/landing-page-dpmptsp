@props(['fill' => '#ffffff', 'flip' => false])
{{-- Bottom wave divider — drop into a section to soften the cut to the next section. --}}
<svg viewBox="0 0 1440 120" preserveAspectRatio="none"
     {{ $attributes->merge(['class' => 'block w-full h-12 md:h-20 ' . ($flip ? 'rotate-180' : '')]) }}
     aria-hidden="true">
    <path d="M0,32 C240,96 480,128 720,96 C960,64 1200,16 1440,32 L1440,120 L0,120 Z" fill="{{ $fill }}"/>
</svg>
