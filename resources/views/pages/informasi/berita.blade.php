@extends('layouts.public')

@section('title', $pageTitle)

@php
    // Small helpers kept local to the news portal view.
    $cover = fn ($post) => $post?->cover_url;
    $catName = fn ($post) => $post?->category?->name;
@endphp

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        title="Berita"
        subtitle="Kabar terbaru seputar investasi, perizinan, dan pelayanan publik DPMPTSP Kota Surabaya." />

    {{-- Filter bar: kanal kategori + pencarian. Sticky di bawah navbar (h-16). --}}
    <div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-slate-100">
        <div class="container-page py-3 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar -mx-1 px-1">
                <a href="{{ route('informasi.berita.index') }}"
                   @class([
                       'shrink-0 rounded-full px-3.5 py-1.5 text-sm font-medium transition whitespace-nowrap',
                       'bg-primary-700 text-white' => ! $activeCategory && ! $searchTerm,
                       'bg-slate-100 text-ink hover:bg-primary-50 hover:text-primary-700' => $activeCategory || $searchTerm,
                   ])>
                    Semua
                </a>
                @foreach ($categories as $c)
                    <a href="{{ route('informasi.berita.index', ['kategori' => $c->slug]) }}"
                       @class([
                           'shrink-0 rounded-full px-3.5 py-1.5 text-sm font-medium transition whitespace-nowrap',
                           'bg-primary-700 text-white' => $activeCategory?->id === $c->id,
                           'bg-slate-100 text-ink hover:bg-primary-50 hover:text-primary-700' => $activeCategory?->id !== $c->id,
                       ])>
                        {{ $c->name }}
                    </a>
                @endforeach
            </div>

            <form method="get" class="relative shrink-0 lg:w-72">
                @if ($activeCategory)
                    <input type="hidden" name="kategori" value="{{ $activeCategory->slug }}">
                @endif
                <input type="search" name="q" value="{{ $searchTerm ?? '' }}" placeholder="Cari berita…"
                       class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                <svg class="w-4 h-4 text-muted absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
            </form>
        </div>
    </div>

    {{-- Headline + sorotan — hanya pada tampilan default (tanpa filter/cari). --}}
    @if ($headline)
        <section class="container-page pt-10">
            <div class="grid gap-6 lg:grid-cols-12">
                <article class="lg:col-span-7 card overflow-hidden group">
                    <a href="{{ route('informasi.berita.show', $headline->slug) }}" class="block overflow-hidden">
                        <div class="aspect-[16/9] bg-gradient-to-br from-primary-100 to-primary-50">
                            @if ($cover($headline))
                                <img src="{{ $cover($headline) }}" alt="" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500" loading="lazy">
                            @endif
                        </div>
                    </a>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs">
                            @if ($catName($headline))
                                <span class="chip">{{ $catName($headline) }}</span>
                            @endif
                            <span class="text-muted">{{ $headline->published_at?->translatedFormat('d F Y') }}</span>
                        </div>
                        <h2 class="mt-3 text-2xl md:text-3xl font-display font-bold text-primary-900 leading-tight">
                            <a href="{{ route('informasi.berita.show', $headline->slug) }}" class="hover:text-primary-700">{{ $headline->title }}</a>
                        </h2>
                        @if ($headline->excerpt)
                            <p class="mt-3 text-muted line-clamp-2">{{ $headline->excerpt }}</p>
                        @endif
                        <a href="{{ route('informasi.berita.show', $headline->slug) }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-700">
                            Baca selengkapnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>

                <div class="lg:col-span-5 flex flex-col">
                    <p class="heading-eyebrow mb-3">Sorotan</p>
                    <div class="flex flex-col divide-y divide-slate-100">
                        @foreach ($secondary as $s)
                            <article class="flex gap-4 py-3 first:pt-0 group">
                                <a href="{{ route('informasi.berita.show', $s->slug) }}" class="shrink-0 w-24 h-20 rounded-xl overflow-hidden bg-gradient-to-br from-primary-100 to-primary-50">
                                    @if ($cover($s))
                                        <img src="{{ $cover($s) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                                    @endif
                                </a>
                                <div class="min-w-0">
                                    <p class="text-xs text-muted">
                                        @if ($catName($s)) <span class="text-primary-700 font-medium">{{ $catName($s) }}</span> · @endif
                                        {{ $s->published_at?->translatedFormat('d M Y') }}
                                    </p>
                                    <h3 class="mt-1 text-sm font-semibold leading-snug line-clamp-3">
                                        <a href="{{ route('informasi.berita.show', $s->slug) }}" class="hover:text-primary-700">{{ $s->title }}</a>
                                    </h3>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Grid berita + sidebar --}}
    <section class="container-page py-12">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div class="flex items-end justify-between mb-6">
                    <h2 class="text-xl font-display font-bold text-primary-900">
                        @if ($searchTerm)
                            Hasil pencarian “{{ $searchTerm }}”
                        @elseif ($activeCategory)
                            Kanal: {{ $activeCategory->name }}
                        @else
                            Berita Terbaru
                        @endif
                    </h2>
                    <span class="text-sm text-muted">{{ number_format($paginator->total(), 0, ',', '.') }} berita</span>
                </div>

                @if ($paginator->isEmpty())
                    <div class="card-padded text-center text-muted py-16">
                        <p class="font-medium text-ink">Belum ada berita yang cocok.</p>
                        <p class="mt-1 text-sm">Coba ubah kata kunci atau pilih kanal lain.</p>
                        <a href="{{ route('informasi.berita.index') }}" class="btn-outline mt-4">Lihat semua berita</a>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2">
                        @foreach ($paginator as $p)
                            <article class="card overflow-hidden group flex flex-col hover:shadow-md transition">
                                <a href="{{ route('informasi.berita.show', $p->slug) }}" class="block overflow-hidden">
                                    <div class="aspect-[16/9] bg-gradient-to-br from-primary-100 to-primary-50">
                                        @if ($cover($p))
                                            <img src="{{ $cover($p) }}" alt="" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500" loading="lazy">
                                        @endif
                                    </div>
                                </a>
                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex items-center gap-2 text-xs text-muted">
                                        @if ($catName($p))
                                            <span class="text-primary-700 font-semibold">{{ $catName($p) }}</span> ·
                                        @endif
                                        {{ $p->published_at?->translatedFormat('d M Y') }}
                                    </div>
                                    <h3 class="mt-2 font-semibold leading-snug line-clamp-2">
                                        <a href="{{ route('informasi.berita.show', $p->slug) }}" class="hover:text-primary-700">{{ $p->title }}</a>
                                    </h3>
                                    @if ($p->excerpt)
                                        <p class="mt-2 text-sm text-muted line-clamp-2 flex-1">{{ $p->excerpt }}</p>
                                    @endif
                                    <div class="mt-3 flex items-center gap-1 text-xs text-muted">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                        {{ number_format($p->view_count, 0, ',', '.') }}x dibaca
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-10">{{ $paginator->links() }}</div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-6">
                <div class="card-padded">
                    <p class="heading-eyebrow">Terpopuler</p>
                    <ol class="mt-4 space-y-4">
                        @foreach ($popular as $i => $pop)
                            <li class="flex gap-3 group">
                                <span class="shrink-0 w-7 h-7 rounded-lg bg-primary-50 text-primary-700 font-bold text-sm grid place-items-center">{{ $i + 1 }}</span>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold leading-snug line-clamp-2">
                                        <a href="{{ route('informasi.berita.show', $pop->slug) }}" class="hover:text-primary-700">{{ $pop->title }}</a>
                                    </h4>
                                    <p class="text-xs text-muted mt-0.5">{{ $pop->published_at?->translatedFormat('d M Y') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                @if ($categories->isNotEmpty())
                    <div class="card-padded">
                        <p class="heading-eyebrow">Kanal Berita</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($categories as $c)
                                <a href="{{ route('informasi.berita.index', ['kategori' => $c->slug]) }}"
                                   class="chip hover:bg-primary-100 transition">{{ $c->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card-padded bg-primary-700 text-white">
                    <p class="font-display font-bold text-lg">Jangan ketinggalan informasi</p>
                    <p class="mt-1 text-sm text-primary-100">Pantau juga pengumuman resmi dan agenda kegiatan DPMPTSP Kota Surabaya.</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('informasi.pengumuman.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold bg-white text-primary-700 hover:bg-primary-50 transition">Pengumuman</a>
                        <a href="{{ route('informasi.agenda.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold border border-white/40 text-white hover:bg-white/10 transition">Agenda</a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
