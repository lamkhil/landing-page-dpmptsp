@extends('layouts.public')

@section('title', $pageTitle)

@php
    $cover    = fn ($post) => $post?->cover_url;
    $catName  = fn ($post) => $post?->category?->name;
    $author   = fn ($post) => $post?->author?->name;
    $initials = function ($name) {
        $name = trim((string) $name);
        if ($name === '') return 'DP';
        $parts = preg_split('/\s+/', $name);
        return strtoupper(mb_substr($parts[0], 0, 1).(isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
    };
    $readMin = fn ($post) => max(1, (int) ceil(str_word_count(strip_tags($post?->body ?? '')) / 200));
@endphp

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        title="Artikel"
        subtitle="Wawasan, kajian, dan ulasan mendalam seputar investasi, perizinan, dan tata kelola pelayanan publik." />

    {{-- Filter bar: topik + pencarian --}}
    <div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-slate-100">
        <div class="container-page py-3 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar -mx-1 px-1">
                <a href="{{ route('informasi.artikel.index') }}"
                   @class([
                       'shrink-0 rounded-full px-3.5 py-1.5 text-sm font-medium transition whitespace-nowrap',
                       'bg-primary-700 text-white' => ! $activeCategory && ! $searchTerm,
                       'bg-slate-100 text-ink hover:bg-primary-50 hover:text-primary-700' => $activeCategory || $searchTerm,
                   ])>
                    Semua Topik
                </a>
                @foreach ($categories as $c)
                    <a href="{{ route('informasi.artikel.index', ['topik' => $c->slug]) }}"
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
                    <input type="hidden" name="topik" value="{{ $activeCategory->slug }}">
                @endif
                <input type="search" name="q" value="{{ $searchTerm ?? '' }}" placeholder="Cari artikel…"
                       class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                <svg class="w-4 h-4 text-muted absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
            </form>
        </div>
    </div>

    {{-- Artikel pilihan + sorotan --}}
    @if ($headline)
        <section class="container-page pt-10">
            <div class="grid gap-6 lg:grid-cols-12">
                <article class="lg:col-span-7 card overflow-hidden group">
                    <a href="{{ route('informasi.artikel.show', $headline->slug) }}" class="block overflow-hidden">
                        <div class="aspect-[16/9] bg-gradient-to-br from-primary-100 to-primary-50">
                            @if ($cover($headline))
                                <img src="{{ $cover($headline) }}" alt="" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500" loading="lazy">
                            @endif
                        </div>
                    </a>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs">
                            <span class="chip">Artikel Pilihan</span>
                            @if ($catName($headline))
                                <span class="text-primary-700 font-semibold">{{ $catName($headline) }}</span>
                            @endif
                        </div>
                        <h2 class="mt-3 text-2xl md:text-3xl font-display font-bold text-primary-900 leading-tight">
                            <a href="{{ route('informasi.artikel.show', $headline->slug) }}" class="hover:text-primary-700">{{ $headline->title }}</a>
                        </h2>
                        @if ($headline->excerpt)
                            <p class="mt-3 text-muted line-clamp-2">{{ $headline->excerpt }}</p>
                        @endif
                        <div class="mt-4 flex items-center gap-3 text-sm">
                            <span class="w-8 h-8 rounded-full bg-primary-700 text-white grid place-items-center text-xs font-bold">{{ $initials($author($headline)) }}</span>
                            <div class="text-muted">
                                <span class="text-ink font-medium">{{ $author($headline) ?? 'Redaksi DPMPTSP' }}</span>
                                · {{ $headline->published_at?->translatedFormat('d M Y') }} · {{ $readMin($headline) }} mnt baca
                            </div>
                        </div>
                    </div>
                </article>

                <div class="lg:col-span-5 flex flex-col">
                    <p class="heading-eyebrow mb-3">Terbaru</p>
                    <div class="flex flex-col divide-y divide-slate-100">
                        @foreach ($secondary as $s)
                            <article class="flex gap-4 py-3 first:pt-0 group">
                                <a href="{{ route('informasi.artikel.show', $s->slug) }}" class="shrink-0 w-24 h-20 rounded-xl overflow-hidden bg-gradient-to-br from-primary-100 to-primary-50">
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
                                        <a href="{{ route('informasi.artikel.show', $s->slug) }}" class="hover:text-primary-700">{{ $s->title }}</a>
                                    </h3>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Grid artikel + sidebar --}}
    <section class="container-page py-12">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div class="flex items-end justify-between mb-6">
                    <h2 class="text-xl font-display font-bold text-primary-900">
                        @if ($searchTerm)
                            Hasil pencarian “{{ $searchTerm }}”
                        @elseif ($activeCategory)
                            Topik: {{ $activeCategory->name }}
                        @else
                            Artikel Terbaru
                        @endif
                    </h2>
                    <span class="text-sm text-muted">{{ number_format($paginator->total(), 0, ',', '.') }} artikel</span>
                </div>

                @if ($paginator->isEmpty())
                    <div class="card-padded text-center text-muted py-16">
                        <p class="font-medium text-ink">Belum ada artikel yang cocok.</p>
                        <p class="mt-1 text-sm">Coba ubah kata kunci atau pilih topik lain.</p>
                        <a href="{{ route('informasi.artikel.index') }}" class="btn-outline mt-4">Lihat semua artikel</a>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2">
                        @foreach ($paginator as $p)
                            <article class="card overflow-hidden group flex flex-col hover:shadow-md transition">
                                <a href="{{ route('informasi.artikel.show', $p->slug) }}" class="block overflow-hidden">
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
                                        {{ $readMin($p) }} mnt baca
                                    </div>
                                    <h3 class="mt-2 font-semibold leading-snug line-clamp-2">
                                        <a href="{{ route('informasi.artikel.show', $p->slug) }}" class="hover:text-primary-700">{{ $p->title }}</a>
                                    </h3>
                                    @if ($p->excerpt)
                                        <p class="mt-2 text-sm text-muted line-clamp-2 flex-1">{{ $p->excerpt }}</p>
                                    @endif
                                    <div class="mt-3 flex items-center gap-2 text-xs text-muted pt-3 border-t border-slate-50">
                                        <span class="w-6 h-6 rounded-full bg-primary-50 text-primary-700 grid place-items-center text-[10px] font-bold">{{ $initials($author($p)) }}</span>
                                        <span class="text-ink font-medium">{{ $author($p) ?? 'Redaksi DPMPTSP' }}</span>
                                        · {{ $p->published_at?->translatedFormat('d M Y') }}
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
                    <p class="heading-eyebrow">Paling Banyak Dibaca</p>
                    <ol class="mt-4 space-y-4">
                        @foreach ($popular as $i => $pop)
                            <li class="flex gap-3 group">
                                <span class="shrink-0 w-7 h-7 rounded-lg bg-primary-50 text-primary-700 font-bold text-sm grid place-items-center">{{ $i + 1 }}</span>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold leading-snug line-clamp-2">
                                        <a href="{{ route('informasi.artikel.show', $pop->slug) }}" class="hover:text-primary-700">{{ $pop->title }}</a>
                                    </h4>
                                    <p class="text-xs text-muted mt-0.5">{{ $pop->published_at?->translatedFormat('d M Y') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                @if ($categories->isNotEmpty())
                    <div class="card-padded">
                        <p class="heading-eyebrow">Topik</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($categories as $c)
                                <a href="{{ route('informasi.artikel.index', ['topik' => $c->slug]) }}"
                                   class="chip hover:bg-primary-100 transition">{{ $c->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card-padded bg-primary-700 text-white">
                    <p class="font-display font-bold text-lg">Ikuti kabar terbaru</p>
                    <p class="mt-1 text-sm text-primary-100">Baca juga berita resmi dan agenda kegiatan DPMPTSP Kota Surabaya.</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('informasi.berita.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold bg-white text-primary-700 hover:bg-primary-50 transition">Berita</a>
                        <a href="{{ route('informasi.agenda.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold border border-white/40 text-white hover:bg-white/10 transition">Agenda</a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
