@extends('layouts.public')

@section('title', $pageTitle)

@php
    $catName = fn ($post) => $post?->category?->name;
    $catSlug = fn ($post) => $post?->category?->slug;
    $color   = fn ($post) => $post?->category?->color ?: '#0E4DA4';

    // Ikon dekoratif per kategori (stroke heroicon-style).
    $catIcons = [
        'perizinan'       => 'M9 12l2 2 4-4M7.5 4h9A1.5 1.5 0 0118 5.5v14L12 17l-6 2.5v-14A1.5 1.5 0 017.5 4z',
        'investasi'       => 'M3 3v18h18M7 14l3-3 3 3 5-6',
        'pelayanan'       => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM5 21a7 7 0 0114 0',
        'zona-integritas' => 'M12 3l8 3v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3zM9 12l2 2 4-4',
        'smart-city'      => 'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M13 9h.01M13 13h.01',
    ];
    $defaultIcon = 'M4 5h16v14H4zM4 15l4-4 3 3 4-5 5 6';
    $iconFor = fn ($post) => $catIcons[$catSlug($post)] ?? $defaultIcon;
    $gradient = fn ($post) => 'background-image:linear-gradient(135deg,'.$color($post).' 0%,'.$color($post).'cc 55%,'.$color($post).'80 100%)';
@endphp

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        title="Infografis"
        subtitle="Sajian visual ringkas seputar perizinan, investasi, dan pelayanan publik DPMPTSP Kota Surabaya — mudah dipahami, mudah dibagikan." />

    {{-- Filter bar: kategori + pencarian --}}
    <div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-slate-100">
        <div class="container-page py-3 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar -mx-1 px-1">
                <a href="{{ route('informasi.infografis.index') }}"
                   @class([
                       'shrink-0 rounded-full px-3.5 py-1.5 text-sm font-medium transition whitespace-nowrap',
                       'bg-primary-700 text-white' => ! $activeCategory && ! $searchTerm,
                       'bg-slate-100 text-ink hover:bg-primary-50 hover:text-primary-700' => $activeCategory || $searchTerm,
                   ])>
                    Semua
                </a>
                @foreach ($categories as $c)
                    <a href="{{ route('informasi.infografis.index', ['kategori' => $c->slug]) }}"
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
                <input type="search" name="q" value="{{ $searchTerm ?? '' }}" placeholder="Cari infografis…"
                       class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                <svg class="w-4 h-4 text-muted absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
            </form>
        </div>
    </div>

    {{-- Infografis unggulan --}}
    @if ($featured->isNotEmpty())
        <section class="container-page pt-10">
            <p class="heading-eyebrow mb-3">Infografis Unggulan</p>
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($featured as $p)
                    <a href="{{ route('informasi.infografis.show', $p->slug) }}" class="card overflow-hidden group flex flex-col hover:shadow-lg transition">
                        <div class="relative h-44 flex items-center justify-center text-white overflow-hidden"
                             style="{{ $gradient($p) }}">
                            @if ($p->cover_path)
                                <img src="{{ asset('storage/'.$p->cover_path) }}" alt="" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                            @else
                                <svg class="w-16 h-16 opacity-90 group-hover:scale-110 transition duration-500" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconFor($p) }}"/>
                                </svg>
                            @endif
                            <span class="absolute top-3 left-3 text-[11px] font-semibold bg-white/20 backdrop-blur px-2 py-0.5 rounded-full">{{ $catName($p) ?? 'Infografis' }}</span>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="font-display font-bold text-primary-900 leading-snug group-hover:text-primary-700">{{ $p->title }}</h3>
                            @if ($p->excerpt)
                                <p class="mt-1.5 text-sm text-muted line-clamp-2 flex-1">{{ $p->excerpt }}</p>
                            @endif
                            <span class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-primary-700">
                                Lihat infografis
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Galeri infografis --}}
    <section class="container-page py-12">
        <div class="flex items-end justify-between mb-6">
            <h2 class="text-xl font-display font-bold text-primary-900">
                @if ($searchTerm)
                    Hasil pencarian “{{ $searchTerm }}”
                @elseif ($activeCategory)
                    Kategori: {{ $activeCategory->name }}
                @else
                    Semua Infografis
                @endif
            </h2>
            <span class="text-sm text-muted">{{ number_format($paginator->total(), 0, ',', '.') }} infografis</span>
        </div>

        @if ($paginator->isEmpty())
            <div class="card-padded text-center text-muted py-16">
                <p class="font-medium text-ink">Belum ada infografis yang cocok.</p>
                <p class="mt-1 text-sm">Coba ubah kata kunci atau pilih kategori lain.</p>
                <a href="{{ route('informasi.infografis.index') }}" class="btn-outline mt-4">Lihat semua infografis</a>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($paginator as $p)
                    <a href="{{ route('informasi.infografis.show', $p->slug) }}" class="card overflow-hidden group flex flex-col hover:shadow-md transition">
                        <div class="relative h-32 flex items-center justify-center text-white overflow-hidden"
                             style="{{ $gradient($p) }}">
                            @if ($p->cover_path)
                                <img src="{{ asset('storage/'.$p->cover_path) }}" alt="" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                            @else
                                <svg class="w-12 h-12 opacity-90 group-hover:scale-110 transition duration-500" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconFor($p) }}"/>
                                </svg>
                            @endif
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            @if ($catName($p))
                                <span class="text-[11px] font-semibold text-primary-700">{{ $catName($p) }}</span>
                            @endif
                            <h3 class="mt-0.5 text-sm font-semibold leading-snug group-hover:text-primary-700 line-clamp-2">{{ $p->title }}</h3>
                            @if ($p->excerpt)
                                <p class="mt-1.5 text-xs text-muted line-clamp-2 flex-1">{{ $p->excerpt }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">{{ $paginator->links() }}</div>
        @endif
    </section>
@endsection
