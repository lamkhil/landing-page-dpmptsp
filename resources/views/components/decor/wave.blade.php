@props(['fill' => '#ffffff', 'flip' => false])
{{-- Bottom wave divider — drop into a section to soften the cut to the next section. --}}
<svg viewBox="0 0 1440 120" preserveAspectRatio="none"
     {{ $attributes->merge(['class' => 'block w-full h-12 md:h-20 -mb-px align-bottom ' . ($flip ? 'rotate-180' : '')]) }}
     aria-hidden="true">
    {{-- Slight horizontal overscan (-2 … 1442) so the filled edges reach past the
         viewport, preventing a thin straight seam at the left/right under
         preserveAspectRatio="none". --}}
    <path d="M-2,32 C240,96 480,128 720,96 C960,64 1200,16 1442,32 L1442,121 L-2,121 Z" fill="{{ $fill }}"/>
</svg>
