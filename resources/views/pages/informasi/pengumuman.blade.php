@extends('layouts.public')

@section('title', $pageTitle)

@php
    $catName = fn ($post) => $post?->category?->name;
@endphp

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        title="Pengumuman"
        subtitle="Pengumuman resmi DPMPTSP Kota Surabaya: kebijakan, jadwal layanan, dan informasi penting bagi masyarakat." />

    {{-- Filter bar: kategori + pencarian --}}
    <div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-slate-100">
        <div class="container-page py-3 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar -mx-1 px-1">
                <a href="{{ route('informasi.pengumuman.index') }}"
                   @class([
                       'shrink-0 rounded-full px-3.5 py-1.5 text-sm font-medium transition whitespace-nowrap',
                       'bg-primary-700 text-white' => ! $activeCategory && ! $searchTerm,
                       'bg-slate-100 text-ink hover:bg-primary-50 hover:text-primary-700' => $activeCategory || $searchTerm,
                   ])>
                    Semua
                </a>
                @foreach ($categories as $c)
                    <a href="{{ route('informasi.pengumuman.index', ['kategori' => $c->slug]) }}"
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
                <input type="search" name="q" value="{{ $searchTerm ?? '' }}" placeholder="Cari pengumuman…"
                       class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                <svg class="w-4 h-4 text-muted absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
            </form>
        </div>
    </div>

    <section class="container-page py-10">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-8 space-y-8">
                {{-- Disematkan --}}
                @if ($pinned->isNotEmpty())
                    <div>
                        <p class="heading-eyebrow mb-3 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M7 4a1 1 0 011-1h8a1 1 0 011 1v9l-5 3-5-3V4z"/></svg>
                            Pengumuman Penting
                        </p>
                        <div class="space-y-3">
                            @foreach ($pinned as $p)
                                <a href="{{ route('informasi.pengumuman.show', $p->slug) }}"
                                   class="block card p-5 border-l-4 border-l-primary-700 bg-primary-50/40 hover:shadow-md transition group">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-primary-700 text-white px-2 py-0.5 font-semibold">Disematkan</span>
                                        @if ($catName($p))<span class="text-primary-700 font-medium">{{ $catName($p) }}</span>@endif
                                        <span class="text-muted">· {{ $p->published_at?->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <h3 class="mt-2 font-display font-bold text-lg text-primary-900 leading-snug group-hover:text-primary-700">{{ $p->title }}</h3>
                                    @if ($p->excerpt)
                                        <p class="mt-1 text-sm text-muted line-clamp-2">{{ $p->excerpt }}</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Daftar pengumuman --}}
                <div>
                    <div class="flex items-end justify-between mb-4">
                        <h2 class="text-xl font-display font-bold text-primary-900">
                            @if ($searchTerm)
                                Hasil pencarian “{{ $searchTerm }}”
                            @elseif ($activeCategory)
                                Kategori: {{ $activeCategory->name }}
                            @else
                                Semua Pengumuman
                            @endif
                        </h2>
                        <span class="text-sm text-muted">{{ number_format($paginator->total(), 0, ',', '.') }} pengumuman</span>
                    </div>

                    @if ($paginator->isEmpty())
                        <div class="card-padded text-center text-muted py-16">
                            <p class="font-medium text-ink">Belum ada pengumuman yang cocok.</p>
                            <p class="mt-1 text-sm">Coba ubah kata kunci atau pilih kategori lain.</p>
                            <a href="{{ route('informasi.pengumuman.index') }}" class="btn-outline mt-4">Lihat semua pengumuman</a>
                        </div>
                    @else
                        <div class="card divide-y divide-slate-100">
                            @foreach ($paginator as $p)
                                <a href="{{ route('informasi.pengumuman.show', $p->slug) }}" class="flex gap-4 p-5 hover:bg-primary-50/40 transition group">
                                    {{-- Blok tanggal --}}
                                    <div class="shrink-0 w-14 text-center">
                                        <div class="rounded-xl bg-primary-50 text-primary-700 py-2">
                                            <div class="text-xl font-bold leading-none">{{ $p->published_at?->format('d') }}</div>
                                            <div class="text-[10px] font-semibold uppercase mt-0.5">{{ $p->published_at?->translatedFormat('M') }}</div>
                                        </div>
                                        <div class="text-[10px] text-muted mt-1">{{ $p->published_at?->format('Y') }}</div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        @if ($catName($p))
                                            <span class="text-xs text-primary-700 font-semibold">{{ $catName($p) }}</span>
                                        @endif
                                        <h3 class="font-semibold leading-snug group-hover:text-primary-700">{{ $p->title }}</h3>
                                        @if ($p->excerpt)
                                            <p class="mt-1 text-sm text-muted line-clamp-2">{{ $p->excerpt }}</p>
                                        @endif
                                    </div>
                                    <svg class="w-5 h-5 text-slate-300 self-center shrink-0 group-hover:text-primary-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-8">{{ $paginator->links() }}</div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-6">
                @if ($categories->isNotEmpty())
                    <div class="card-padded">
                        <p class="heading-eyebrow">Kategori</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($categories as $c)
                                <a href="{{ route('informasi.pengumuman.index', ['kategori' => $c->slug]) }}"
                                   class="chip hover:bg-primary-100 transition">{{ $c->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card-padded">
                    <p class="heading-eyebrow">Tautan Cepat</p>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('informasi.berita.index') }}" class="flex items-center gap-2 text-ink hover:text-primary-700"><span class="w-1.5 h-1.5 rounded-full bg-primary-700"></span> Berita Terbaru</a></li>
                        <li><a href="{{ route('informasi.agenda.index') }}" class="flex items-center gap-2 text-ink hover:text-primary-700"><span class="w-1.5 h-1.5 rounded-full bg-primary-700"></span> Agenda Kegiatan</a></li>
                        <li><a href="{{ route('informasi.dokumen-publik') }}" class="flex items-center gap-2 text-ink hover:text-primary-700"><span class="w-1.5 h-1.5 rounded-full bg-primary-700"></span> Dokumen Publik</a></li>
                    </ul>
                </div>

                <div class="card-padded bg-primary-700 text-white">
                    <p class="font-display font-bold text-lg">Punya pertanyaan?</p>
                    <p class="mt-1 text-sm text-primary-100">Sampaikan pengaduan atau konsultasi layanan melalui kanal resmi kami.</p>
                    <a href="{{ route('pengaduan.index') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold bg-white text-primary-700 hover:bg-primary-50 transition">Pusat Pengaduan</a>
                </div>
            </aside>
        </div>
    </section>
@endsection
