@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Visi dan Misi DPMPTSP Kota Surabaya selaras dengan RPJMD Kota Surabaya 2021–2026.'), 160))

@section('content')

    {{-- ════════════════════════════════════════════════════════════════
     HERO — dark gradient, eyebrow, title, RPJMD context
     ════════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white overflow-hidden">
        <x-decor.grid-lines class="opacity-50" color="rgb(255 255 255 / 0.04)" />
        <x-decor.dots class="top-8 right-8 w-64 h-64 opacity-60" color="rgb(34 211 238 / 0.25)" />
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-accent-500/10 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full bg-primary-500/15 blur-3xl"></div>
        <img src="/brand/icon_batik_hijau_kanan.svg" alt="" aria-hidden="true"
            class="absolute -bottom-2 right-0 w-40 md:w-56 lg:w-72 opacity-25 pointer-events-none mix-blend-screen" />

        <div class="container-page py-14 md:py-20 relative">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-300" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span class="text-white/30">/</span>
                <a href="{{ route('profil.index') }}" class="hover:text-white">Profil</a>
                <span class="text-white/30">/</span>
                <span class="text-white font-medium">Visi &amp; Misi</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Visi &amp; Misi DPMPTSP Kota Surabaya' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $post?->excerpt ?? 'Visi dan Misi DPMPTSP Surabaya selaras dengan RPJMD Kota Surabaya 2021–2026 — gotong royong menuju Surabaya kota dunia yang maju, humanis, dan berkelanjutan.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('informasi.dokumen.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5 hover:bg-white/20 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Selaras RPJMD 2021–2026
                    </a>
                    <a href="{{ route('informasi.dokumen.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5 hover:bg-white/20 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Renstra DPMPTSP
                    </a>
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
                <p class="mt-2 text-muted">Halaman Visi &amp; Misi sedang disiapkan dan akan segera tersedia.</p>
                <a href="{{ route('profil.index') }}" class="btn-ghost mt-5">← Kembali ke Profil</a>
            </div>
        </section>
    @else

        {{-- ════════════════════════════════════════════════════════════════
         VISI — centerpiece quote card
         ════════════════════════════════════════════════════════════════ --}}
        @if ($visi)
            <section class="container-page py-14 lg:py-20">
                <div class="relative max-w-4xl mx-auto bg-gradient-to-br from-primary-700 to-primary-900 rounded-3xl p-8 md:p-12 lg:p-16 text-white shadow-xl shadow-primary-950/20 overflow-hidden">
                    <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.04)" />
                    <x-decor.dots class="-top-6 -right-6 w-40 h-40" color="rgb(34 211 238 / 0.30)" />
                    {{-- Big decorative quote mark --}}
                    <svg class="absolute top-6 left-6 w-20 h-20 text-accent-400/20" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/>
                    </svg>
                    <div class="relative text-center">
                        <p class="text-xs font-bold tracking-[0.2em] uppercase text-accent-400">Visi</p>
                        <blockquote class="mt-5">
                            <p class="font-display font-bold text-2xl md:text-3xl lg:text-4xl leading-snug tracking-tight">
                                &ldquo;{{ $visi }}&rdquo;
                            </p>
                        </blockquote>
                        <div class="mt-7 inline-flex items-center gap-2 text-sm text-slate-300">
                            <span class="h-px w-8 bg-accent-400/50"></span>
                            Visi Walikota Surabaya 2021–2026
                            <span class="h-px w-8 bg-accent-400/50"></span>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         MISI — numbered cards
         ════════════════════════════════════════════════════════════════ --}}
        @if (count($misi))
            @php
                $misiItems = [];
                foreach ($misi as $idx => $m) {
                    $misiItems[] = ['eyebrow' => 'Misi DPMPTSP', 'title' => 'Misi '.($idx + 1), 'desc' => $m['body'], 'docs' => $m['docs']];
                }
            @endphp
            <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden"
                x-data="{ open: false, i: 0, items: @js($misiItems) }"
                x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''">
                <x-decor.dots class="-bottom-10 -left-10 w-80 h-80 opacity-50" color="rgb(14 77 164 / 0.10)" />
                <div class="container-page py-16 lg:py-20 relative">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Misi</p>
                        <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Langkah strategis menuju <span class="italic text-primary-700">visi</span></h2>
                        <p class="mt-2 text-sm text-muted">Tiga misi utama yang menjadi arah kerja DPMPTSP Kota Surabaya dalam pelayanan publik dan penanaman modal. Klik kartu untuk melihat detail dan dasar dokumen.</p>
                    </div>
                    <div class="mt-8 grid gap-5 md:grid-cols-3">
                        @foreach ($misi as $i => $m)
                            <button type="button" @click="i = {{ $i }}; open = true"
                                class="group relative text-left bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg hover:-translate-y-0.5 hover:border-primary-200 transition-all cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-primary-700 text-white grid place-items-center font-display font-bold text-lg shrink-0">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <span class="heading-eyebrow">Misi {{ $i + 1 }}</span>
                                </div>
                                <p class="mt-4 text-sm text-ink leading-relaxed line-clamp-4">{{ $m['body'] }}</p>
                                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-end">
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
         FOKUS STRATEGIS — pillar cards (dark)
         ════════════════════════════════════════════════════════════════ --}}
        @if (count($fokus))
            @php
                $fokusIcons = [
                    'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', // mendorong investasi (trending up)
                    'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', // pelayanan prima (check shield)
                    'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', // transformasi digital (monitor)
                ];
                $fokusItems = [];
                foreach ($fokus as $f) {
                    $fokusItems[] = ['eyebrow' => 'Fokus Strategis', 'title' => $f['title'], 'desc' => $f['body'], 'docs' => $f['docs']];
                }
            @endphp
            <section class="relative bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white overflow-hidden"
                x-data="{ open: false, i: 0, items: @js($fokusItems) }"
                x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''">
                <x-decor.grid-lines class="opacity-50" color="rgb(255 255 255 / 0.04)" />
                <x-decor.dots class="top-10 right-10 w-72 h-72 opacity-50" color="rgb(34 211 238 / 0.20)" />
                <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full bg-accent-500/10 blur-3xl"></div>
                <div class="container-page py-16 lg:py-24 relative">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold tracking-widest uppercase text-accent-400">Fokus Strategis</p>
                        <h2 class="mt-2 text-2xl md:text-4xl font-display font-bold leading-tight">Tiga fokus <em class="not-italic text-accent-400">DPMPTSP Surabaya</em></h2>
                        <p class="mt-4 text-slate-200 leading-relaxed">Penjabaran misi ke dalam fokus kerja yang terukur — mendorong investasi, pelayanan perizinan prima, dan transformasi digital. Klik kartu untuk detail.</p>
                    </div>
                    <div class="mt-10 grid gap-5 md:grid-cols-3">
                        @foreach ($fokus as $i => $f)
                            <button type="button" @click="i = {{ $i }}; open = true"
                                class="group text-left bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 hover:bg-white/10 hover:-translate-y-0.5 transition-all cursor-pointer">
                                <div class="w-12 h-12 rounded-xl bg-accent-500/20 grid place-items-center text-accent-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $fokusIcons[$i] ?? $fokusIcons[0] }}" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 font-display font-bold text-lg leading-snug">{{ $f['title'] }}</h3>
                                @if ($f['body'])
                                    <p class="mt-2 text-sm text-slate-300 leading-relaxed line-clamp-3">{{ $f['body'] }}</p>
                                @endif
                                <span class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-accent-400">
                                    Detail
                                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <x-profil.detail-modal />
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         FALLBACK — if parsing produced nothing usable, render raw CMS body
         so no content is ever lost.
         ════════════════════════════════════════════════════════════════ --}}
        @if (!$visi && !count($misi) && !count($fokus))
            <section class="container-page py-14">
                <div class="card-padded max-w-2xl mx-auto">
                    <p class="heading-eyebrow">Belum ada data</p>
                    <h2 class="mt-2 text-xl font-semibold text-primary-900">Konten sedang disiapkan</h2>
                    <p class="mt-2 text-muted">Visi, Misi, dan Fokus Strategis dapat ditambahkan melalui CMS (Profil → Visi, Misi, Tugas &amp; Fungsi).</p>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         DOKUMEN & NAVIGASI PROFIL
         ════════════════════════════════════════════════════════════════ --}}
        <section class="container-page py-16 lg:py-20">
            <div class="grid lg:grid-cols-2 gap-6">
                {{-- Source / document --}}
                <div class="relative bg-gradient-to-br from-primary-50 to-white border border-primary-100 rounded-2xl p-8 overflow-hidden">
                    <x-decor.dots class="top-0 right-0 w-40 h-40 opacity-70" color="rgb(14 77 164 / 0.10)" />
                    <div class="relative">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Dokumen Resmi</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Renstra &amp; RPJMD</h2>
                        <p class="mt-3 text-sm text-muted leading-relaxed">Visi dan Misi mengacu pada Rencana Strategis DPMPTSP serta RPJMD Kota Surabaya 2021–2026. Dokumen lengkap tersedia di Download Center.</p>
                        <a href="{{ route('informasi.dokumen.index') }}" class="btn-primary mt-5">
                            Unduh Dokumen
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                        </a>
                    </div>
                </div>

                {{-- Profil navigation --}}
                <div class="bg-white border border-slate-100 rounded-2xl p-8">
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
                    <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Jelajahi Profil DPMPTSP</h2>
                    <div class="mt-5 grid sm:grid-cols-2 gap-3">
                        @foreach ([['Profil DPMPTSP', 'profil.index'], ['Struktur Organisasi', 'profil.struktur'], ['Tugas & Fungsi', 'profil.tugas-fungsi'], ['Reformasi Birokrasi', 'profil.reformasi']] as [$label, $routeName])
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
