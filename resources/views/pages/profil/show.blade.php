@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? ''), 160))

@php
    // Profil sub-menu for the sidebar nav (static layout; labels match the navbar).
    $profilNav = [
        ['Profil DPMPTSP', 'profil.index'],
        ['Visi & Misi', 'profil.visi-misi'],
        ['Struktur Organisasi', 'profil.struktur'],
        ['Tugas & Fungsi', 'profil.tugas-fungsi'],
        ['Maklumat Pelayanan', 'profil.maklumat'],
        ['SOP Pelayanan', 'profil.sop'],
        ['Standar Pelayanan', 'profil.standar'],
        ['Reformasi Birokrasi', 'profil.reformasi'],
        ['WBK', 'profil.wbk'],
        ['WBBM', 'profil.wbbm'],
        ['Mengapa Surabaya', 'profil.mengapa'],
        ['FAQ', 'profil.faq'],
    ];
@endphp

@section('content')

    {{-- ════════════════════════════════════════════════════════════════
     HERO
     ════════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white overflow-hidden">
        <x-decor.grid-lines class="opacity-50" color="rgb(255 255 255 / 0.04)" />
        <x-decor.dots class="top-8 right-8 w-64 h-64 opacity-60" color="rgb(34 211 238 / 0.25)" />
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-accent-500/10 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full bg-primary-500/15 blur-3xl"></div>
        <img src="/brand/icon_batik_hijau_kanan.svg" alt="" aria-hidden="true"
            class="absolute -bottom-2 right-0 w-40 md:w-56 lg:w-72 opacity-25 pointer-events-none mix-blend-screen" />

        <div class="container-page py-14 md:py-20 relative">
            <nav class="flex items-center gap-2 text-sm text-slate-300" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span class="text-white/30">/</span>
                <a href="{{ route('profil.index') }}" class="hover:text-white">Profil</a>
                <span class="text-white/30">/</span>
                <span class="text-white font-medium">{{ $post?->title ?? $fallbackTitle }}</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? $fallbackTitle }}
                </h1>
                @if ($post?->excerpt)
                    <p class="mt-5 text-slate-200 text-lg leading-relaxed">{{ $post->excerpt }}</p>
                @endif
            </div>
        </div>
        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     CONTENT + PROFIL SIDEBAR
     ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8">
                @if ($post)
                    @if ($post->cover_path)
                        <figure class="mb-8 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <img src="{{ asset('storage/'.$post->cover_path) }}" alt="{{ $post->title }}"
                                class="mx-auto w-auto max-w-full max-h-96 object-contain bg-slate-50 p-4" loading="lazy">
                        </figure>
                    @endif
                    <article class="prose prose-slate max-w-none
                        prose-headings:font-display prose-headings:text-primary-900
                        prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4
                        prose-a:text-primary-700 prose-a:font-medium
                        prose-strong:text-ink prose-img:rounded-2xl prose-img:border prose-img:border-slate-100
                        prose-blockquote:border-primary-300 prose-blockquote:bg-primary-50/40 prose-blockquote:rounded-r-xl prose-blockquote:py-1 prose-blockquote:not-italic
                        prose-li:marker:text-primary-500">
                        {!! $post->body !!}
                    </article>
                @else
                    <div class="card-padded">
                        <p class="heading-eyebrow">Belum dipublikasi</p>
                        <h2 class="mt-2 text-xl font-semibold text-primary-900">Konten sedang disiapkan</h2>
                        <p class="mt-2 text-muted">Halaman ini sedang disiapkan dan akan segera tersedia.</p>
                        <a href="{{ route('profil.index') }}" class="btn-ghost mt-5">← Kembali ke Profil</a>
                    </div>
                @endif
            </div>

            {{-- Sidebar: profil sub-menu --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-24 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-100">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Halaman Profil</p>
                    </div>
                    <nav class="p-2">
                        @foreach ($profilNav as [$label, $routeName])
                            @php $active = request()->routeIs($routeName); @endphp
                            <a href="{{ route($routeName) }}"
                                class="flex items-center justify-between gap-2 rounded-xl px-3 py-2.5 text-sm transition
                                    {{ $active ? 'bg-primary-700 text-white font-semibold' : 'text-ink hover:bg-primary-50 hover:text-primary-700' }}">
                                <span>{{ $label }}</span>
                                @if ($active)
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                @endif
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>
        </div>
    </section>
@endsection
