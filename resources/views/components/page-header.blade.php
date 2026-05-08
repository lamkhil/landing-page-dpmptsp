@props(['eyebrow' => null, 'title', 'subtitle' => null])

<section class="bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 text-white">
    <div class="container-page py-14 md:py-20">
        @if ($eyebrow)
            <p class="heading-eyebrow text-accent-400">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-2 font-display font-extrabold text-3xl md:text-5xl tracking-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-4 max-w-2xl text-slate-200 text-lg">{{ $subtitle }}</p>
        @endif
    </div>
</section>
