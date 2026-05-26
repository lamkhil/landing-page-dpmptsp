@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Susunan organisasi DPMPTSP Kota Surabaya — Kepala Dinas, Sekretariat, dan bidang pelaksana.'), 160))

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
                <span class="text-white font-medium">Struktur Organisasi</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Struktur Organisasi' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $post?->excerpt ?? 'Susunan organisasi DPMPTSP Kota Surabaya — Kepala Dinas, Sekretariat, dan bidang-bidang pelaksana tugas pelayanan perizinan dan penanaman modal.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('informasi.regulasi.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5 hover:bg-white/20 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Perwali No. 52 Tahun 2023
                    </a>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Diperbarui 2026
                    </span>
                </div>
            </div>
        </div>
        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>
    </section>

    @if (!$post)
        {{-- ─── Not published yet ─── --}}
        <section class="container-page py-16">
            <div class="card-padded max-w-2xl">
                <p class="heading-eyebrow">Belum dipublikasi</p>
                <h2 class="mt-2 text-xl font-semibold text-primary-900">Konten sedang disiapkan</h2>
                <p class="mt-2 text-muted">Halaman Struktur Organisasi sedang disiapkan dan akan segera tersedia.</p>
                <a href="{{ route('profil.index') }}" class="btn-ghost mt-5">← Kembali ke Profil</a>
            </div>
        </section>
    @else

        {{-- ════════════════════════════════════════════════════════════════
         INTRO + BAGAN (org-chart image, click to zoom)
         ════════════════════════════════════════════════════════════════ --}}
        <section class="container-page py-14 lg:py-16">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                @if ($intro)
                    <div class="lg:col-span-4">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Dasar Pembentukan</p>
                        <h2 class="mt-1 text-2xl font-display font-bold text-ink">Landasan Organisasi</h2>
                        <p class="mt-4 text-sm text-muted leading-relaxed">{{ $intro }}</p>
                    </div>
                @endif

                @if ($chartImage)
                    <div class="{{ $intro ? 'lg:col-span-8' : 'lg:col-span-12' }}"
                        x-data="{ zoom: false }"
                        x-effect="document.documentElement.style.overflow = zoom ? 'hidden' : ''">
                        <figure class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <button type="button" @click="zoom = true"
                                class="group block w-full relative cursor-zoom-in bg-slate-50" aria-label="Perbesar bagan struktur organisasi">
                                <img src="{{ $chartImage }}"
                                    alt="Bagan Struktur Organisasi DPMPTSP Kota Surabaya"
                                    class="mx-auto w-auto max-w-full max-h-72 lg:max-h-80 object-contain p-4" loading="lazy">
                                <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur text-primary-700 text-xs font-semibold px-3 py-1.5 shadow-sm opacity-0 group-hover:opacity-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m-3-3h6" /></svg>
                                    Perbesar
                                </span>
                            </button>
                            <figcaption class="flex items-center gap-2 px-5 py-3 text-xs text-muted border-t border-slate-100">
                                <svg class="w-3.5 h-3.5 shrink-0 text-primary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Bagan Struktur Organisasi DPMPTSP Kota Surabaya
                            </figcaption>
                        </figure>

                        {{-- Zoom modal — scroll happens inside the overlay, page behind is locked --}}
                        <div x-cloak x-show="zoom" x-transition.opacity
                            @keydown.escape.window="zoom = false"
                            class="fixed inset-0 z-[60] bg-primary-950/90 backdrop-blur-sm"
                            role="dialog" aria-modal="true" aria-label="Bagan struktur organisasi diperbesar">
                            <button type="button" @click="zoom = false"
                                class="fixed top-4 right-4 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 text-white grid place-items-center transition" aria-label="Tutup">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            {{-- This wrapper scrolls; image shown at full size so it can be panned --}}
                            <div class="absolute inset-0 overflow-auto overscroll-contain" @click="zoom = false">
                                <div class="min-h-full min-w-full grid place-items-center p-4 md:p-10">
                                    <img src="{{ $chartImage }}" @click.stop
                                        alt="Bagan Struktur Organisasi DPMPTSP Kota Surabaya"
                                        class="max-w-none rounded-xl bg-white shadow-2xl">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- ════════════════════════════════════════════════════════════════
         SUSUNAN ORGANISASI — hierarchy (leader card + unit grid)
         ════════════════════════════════════════════════════════════════ --}}
        @if ($leader || count($units))
            @php
                // Generic department icons, cycled by index. Robust to any unit naming.
                $unitIcons = [
                    'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01', // building
                    'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3m4-14h.01M11 7h2m-2 4h2m-2 4h2', // office
                    'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', // book
                    'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', // data
                    'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z', // people
                ];
                // Build the modal item list from structured units (leader first,
                // index 0). Map controller keys → modal keys (name→title, …).
                $toItem = fn ($u) => [
                    'eyebrow'  => $u['eyebrow'],
                    'title'    => $u['name'],
                    'desc'     => $u['description'],
                    'children' => $u['children'],
                    'docs'     => $u['docs'],
                ];
                $detailItems = [];
                if ($leader) {
                    $detailItems[] = $toItem($leader);
                }
                foreach ($units as $u) {
                    $detailItems[] = $toItem($u);
                }
            @endphp
            <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden"
                x-data="{ open: false, i: 0, items: @js($detailItems) }"
                x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''">
                <x-decor.dots class="-bottom-10 -left-10 w-80 h-80 opacity-50" color="rgb(14 77 164 / 0.10)" />
                <div class="container-page py-16 lg:py-20 relative">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Susunan Organisasi</p>
                        <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Hierarki <span class="italic text-primary-700">DPM-PTSP</span></h2>
                        <p class="mt-2 text-sm text-muted">Susunan pejabat struktural dan unit kerja DPM-PTSP Kota Surabaya. Klik kartu unit untuk melihat detail, tim kerja, dan dasar hukum.</p>
                    </div>

                    {{-- Pimpinan (first unit) — clickable --}}
                    @if ($leader)
                        <div class="mt-8 max-w-2xl mx-auto">
                            <button type="button" @click="i = 0; open = true"
                                class="group block w-full relative bg-gradient-to-br from-primary-700 to-primary-900 rounded-2xl p-7 md:p-8 text-white text-center shadow-xl shadow-primary-950/20 overflow-hidden hover:shadow-2xl hover:-translate-y-0.5 transition-all cursor-pointer">
                                <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.05)" />
                                <x-decor.dots class="-top-4 -right-4 w-28 h-28" color="rgb(34 211 238 / 0.30)" />
                                <div class="relative">
                                    <div class="w-12 h-12 mx-auto rounded-xl bg-accent-500/20 grid place-items-center text-accent-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <p class="mt-3 text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Pimpinan</p>
                                    <h3 class="mt-1 text-xl md:text-2xl font-display font-bold">{{ $leader['name'] }}</h3>
                                    @if ($leader['description'])
                                        <p class="mt-2 text-sm text-slate-200 max-w-md mx-auto leading-relaxed">{{ $leader['description'] }}</p>
                                    @endif
                                    <span class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-accent-400">
                                        Lihat detail
                                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </span>
                                </div>
                            </button>
                            {{-- connector --}}
                            <div class="flex justify-center" aria-hidden="true">
                                <span class="h-8 w-px bg-slate-300"></span>
                            </div>
                        </div>
                    @endif

                    {{-- Unit kerja — every card opens the detail modal --}}
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($units as $i => $unit)
                            <button type="button" @click="i = {{ $leader ? $i + 1 : $i }}; open = true"
                                class="group text-left bg-white rounded-2xl border border-slate-100 p-6 transition-all hover:shadow-lg hover:-translate-y-0.5 hover:border-primary-200 cursor-pointer">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 grid place-items-center shrink-0 group-hover:bg-primary-700 group-hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $unitIcons[$i % count($unitIcons)] }}" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-300 font-display">{{ str_pad($i + 2, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <h3 class="mt-4 font-display font-bold text-ink leading-snug group-hover:text-primary-700 transition">{{ $unit['name'] }}</h3>
                                @if ($unit['description'])
                                    <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $unit['description'] }}</p>
                                @endif
                                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                    @if (count($unit['children']))
                                        <span class="chip">{{ count($unit['children']) }} tim kerja</span>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-primary-700">
                                        Detail
                                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <x-profil.detail-modal />
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         EMPTY STATE — no structured units/intro/chart yet.
         ════════════════════════════════════════════════════════════════ --}}
        @if (!$intro && !$chartImage && !$leader && !count($units))
            <section class="container-page py-14">
                <div class="card-padded max-w-2xl mx-auto">
                    <p class="heading-eyebrow">Belum ada data</p>
                    <h2 class="mt-2 text-xl font-semibold text-primary-900">Konten sedang disiapkan</h2>
                    <p class="mt-2 text-muted">Susunan organisasi dapat ditambahkan melalui CMS (Profil → Struktur Organisasi).</p>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         DASAR HUKUM & NAVIGASI PROFIL
         ════════════════════════════════════════════════════════════════ --}}
        <section class="container-page py-16 lg:py-20">
            <div class="grid lg:grid-cols-2 gap-6">
                <div class="relative bg-gradient-to-br from-primary-50 to-white border border-primary-100 rounded-2xl p-8 overflow-hidden">
                    <x-decor.dots class="top-0 right-0 w-40 h-40 opacity-70" color="rgb(14 77 164 / 0.10)" />
                    <div class="relative">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Dasar Hukum</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Peraturan Walikota Surabaya</h2>
                        <p class="mt-3 text-sm text-muted leading-relaxed">Susunan organisasi ditetapkan melalui Peraturan Walikota Surabaya tentang Kedudukan, Susunan Organisasi, Tugas, Fungsi dan Tata Kerja DPM-PTSP.</p>
                        <a href="{{ route('informasi.regulasi.index') }}" class="btn-primary mt-5">
                            Lihat Regulasi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 rounded-2xl p-8">
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
                    <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Jelajahi Profil DPMPTSP</h2>
                    <div class="mt-5 grid sm:grid-cols-2 gap-3">
                        @foreach ([['Profil DPMPTSP', 'profil.index'], ['Visi & Misi', 'profil.visi-misi'], ['Tugas & Fungsi', 'profil.tugas-fungsi'], ['Reformasi Birokrasi', 'profil.reformasi']] as [$label, $routeName])
                            <a href="{{ route($routeName) }}" class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:border-primary-200 hover:bg-primary-50/50 transition">
                                <span class="text-sm font-semibold text-ink group-hover:text-primary-700">{{ $label }}</span>
                                <svg class="w-4 h-4 text-muted group-hover:text-primary-700 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
