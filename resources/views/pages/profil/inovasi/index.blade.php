@extends('layouts.public')

@section('title', $pageTitle)
@section('meta_description', 'Inovasi pelayanan DPMPTSP Kota Surabaya — sistem perizinan, dashboard manajerial, klinik investasi, chatbot, dan pengakuan HAKI.')

@section('content')
    <x-page-header
        eyebrow="Profil DPMPTSP"
        title="Inovasi DPMPTSP Surabaya"
        subtitle="Sederet inovasi digital yang mempermudah akses, mempercepat proses, dan meningkatkan kualitas layanan kepada masyarakat dan pelaku usaha." />

    <section class="container-page py-10 lg:py-14"
             x-data="{ activeCat: 'all' }">

        @if ($items->isEmpty())
            <div class="card-padded max-w-2xl">
                <p class="heading-eyebrow">Belum tersedia</p>
                <p class="mt-2 text-muted">Daftar inovasi sedang disiapkan dan akan segera dipublikasikan.</p>
            </div>
        @else
            {{-- Chip filter (tablist) — client-side, instant. Reuses .chip
                 component class; active state inverts the chip. --}}
            <div role="tablist" aria-label="Filter kategori inovasi"
                 class="flex flex-wrap items-center gap-2">
                <button type="button" role="tab"
                        @click="activeCat = 'all'"
                        :aria-selected="activeCat === 'all'"
                        :class="activeCat === 'all'
                            ? 'bg-primary-700 text-white shadow-sm'
                            : 'bg-primary-50 text-primary-700 hover:bg-primary-100'"
                        class="inline-flex items-center gap-1.5 rounded-full text-sm font-medium px-4 py-1.5 transition">
                    Semua
                    <span class="text-[11px] opacity-70">{{ $items->count() }}</span>
                </button>
                @foreach ($categories as $cat)
                    <button type="button" role="tab"
                            @click="activeCat = '{{ $cat->slug }}'"
                            :aria-selected="activeCat === '{{ $cat->slug }}'"
                            :class="activeCat === '{{ $cat->slug }}'
                                ? 'bg-primary-700 text-white shadow-sm'
                                : 'bg-primary-50 text-primary-700 hover:bg-primary-100'"
                            class="inline-flex items-center gap-1.5 rounded-full text-sm font-medium px-4 py-1.5 transition">
                        {{ $cat->name }}
                        <span class="text-[11px] opacity-70">{{ $items->where('category_id', $cat->id)->count() }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Card grid — flat, no per-category sub-headings. Cards carry
                 data-category so Alpine can show/hide based on activeCat. --}}
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($items as $post)
                    @php $catSlug = $post->category?->slug ?? 'lainnya'; @endphp
                    <a href="{{ route('profil.inovasi.show', $post->slug) }}"
                       x-show="activeCat === 'all' || activeCat === '{{ $catSlug }}'"
                       data-category="{{ $catSlug }}"
                       class="group card overflow-hidden block hover:shadow-md transition border-slate-100 hover:border-primary-200">
                        {{-- Cover --}}
                        <div class="relative aspect-[16/9] bg-slate-50 overflow-hidden">
                            @if ($post->cover_path)
                                <img src="{{ asset('storage/'.$post->cover_path) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-[1.02] transition duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full grid place-items-center text-primary-200">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4l3 3h6v13H3V7h6l3-3z"/></svg>
                                </div>
                            @endif
                            @if ($post->is_featured)
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full bg-accent-500 text-primary-950 text-[10px] font-bold tracking-widest uppercase px-2.5 py-1 shadow">
                                    Unggulan
                                </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-5">
                            @if ($post->category)
                                <p class="heading-eyebrow text-xs">{{ $post->category->name }}</p>
                            @endif
                            <h3 class="mt-2 font-display font-bold text-lg text-primary-900 group-hover:text-primary-700 transition">
                                {{ $post->title }}
                            </h3>
                            <p class="mt-2 text-sm text-muted line-clamp-3">{{ $post->excerpt }}</p>
                            <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary-700">
                                Selengkapnya
                                <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
