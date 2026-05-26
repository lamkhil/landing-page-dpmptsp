@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Reformasi Birokrasi DPMPTSP Kota Surabaya — 6 area perubahan pembangunan Zona Integritas menuju WBK/WBBM.'), 160))

@php
    // Icons for the 6 ZI areas of change (cycled by index).
    $areaIcons = [
        'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', // manajemen perubahan
        'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5', // tata laksana
        'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z', // SDM
        'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z', // akuntabilitas
        'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z', // pengawasan
        'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', // pelayanan publik
    ];
    $areaItems = collect($areas)->map(fn ($a) => [
        'eyebrow'    => $a['eyebrow'] ?? 'Area Perubahan',
        'title'      => $a['title'],
        'desc'       => $a['desc'],
        'sasaran'    => $a['sasaran'] ?? [],
        'indikator'  => $a['indikator'] ?? [],
        'agents'     => $a['agents'] ?? [],
        'agentsNote' => $a['agentsNote'] ?? null,
        'docs'       => $a['docs'],
    ])->all();
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
                <span class="text-white font-medium">Reformasi Birokrasi</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Reformasi Birokrasi' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $intro ?? 'Pembangunan Zona Integritas DPMPTSP Kota Surabaya melalui 6 area perubahan menuju Wilayah Bebas dari Korupsi (WBK) dan Wilayah Birokrasi Bersih dan Melayani (WBBM).' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        {{ count($areas) }} Area Perubahan
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Zona Integritas WBK / WBBM
                    </span>
                </div>
            </div>
        </div>
        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     TIM PEMBANGUNAN ZI — pimpinan tim (Ketua & Sekretaris)
     ════════════════════════════════════════════════════════════════ --}}
    @if (!empty($pimpinan))
        <section class="container-page pt-12 lg:pt-16">
            <div class="bg-white border border-slate-100 rounded-2xl p-8 lg:p-10">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Tim Pembangunan Zona Integritas</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Pimpinan Tim ZI</h2>
                    @if (!empty($skNote))
                        <p class="mt-2 text-sm text-muted">
                            Berdasarkan
                            @if (!empty($skNote['url']))
                                <a href="{{ $skNote['url'] }}" target="_blank" rel="noopener" class="font-semibold text-primary-700 hover:underline">{{ $skNote['label'] }}</a>
                            @else
                                <span class="font-semibold text-ink">{{ $skNote['label'] }}</span>
                            @endif
                        </p>
                    @endif
                </div>
                <div class="mt-6 grid sm:grid-cols-2 gap-4">
                    @foreach ($pimpinan as $p)
                        <div class="flex items-center gap-4 rounded-xl border border-slate-100 p-4 hover:border-primary-200 transition">
                            @if (!empty($p['photo']))
                                <img src="{{ $p['photo'] }}" alt="{{ $p['name'] }}" class="w-14 h-14 rounded-full object-cover shrink-0" />
                            @else
                                <span class="w-14 h-14 rounded-full bg-primary-50 text-primary-700 grid place-items-center shrink-0 text-base font-bold font-display">{{ \Illuminate\Support\Str::of($p['name'])->explode(' ')->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                            @endif
                            <div class="min-w-0">
                                @if (!empty($p['role']))
                                    <span class="inline-block text-[10px] font-bold tracking-wide uppercase px-1.5 py-0.5 rounded bg-accent-500/15 text-primary-800">{{ $p['role'] }}</span>
                                @endif
                                <p class="mt-1 text-base font-semibold text-ink truncate">{{ $p['name'] }}</p>
                                @if (!empty($p['position']))
                                    <p class="text-xs text-muted truncate">{{ $p['position'] }}</p>
                                @endif
                                @if (!empty($p['nip']))
                                    <p class="text-xs text-muted truncate">NIP {{ $p['nip'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ════════════════════════════════════════════════════════════════
     6 AREA PERUBAHAN — cards → detail modal
     ════════════════════════════════════════════════════════════════ --}}
    @if (count($areas))
        <section class="container-page py-16 lg:py-20"
            x-data="{ open: false, i: 0, items: @js($areaItems) }"
            x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''">
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Area Perubahan</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">6 area perubahan <span class="italic text-primary-700">Zona Integritas</span></h2>
                <p class="mt-2 text-sm text-muted">Area pengungkit pembangunan Zona Integritas DPMPTSP Kota Surabaya. Klik tiap area untuk melihat program & sasarannya.</p>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($areas as $i => $area)
                    <button type="button" @click="i = {{ $i }}; open = true"
                        class="group text-left bg-white rounded-2xl border border-slate-100 p-6 flex flex-col hover:shadow-lg hover:-translate-y-0.5 hover:border-primary-200 transition-all cursor-pointer">
                        <div class="flex items-start justify-between gap-3">
                            <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-700 grid place-items-center shrink-0 group-hover:bg-primary-700 group-hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $areaIcons[$i % count($areaIcons)] }}" /></svg>
                            </div>
                            <span class="text-2xl font-display font-extrabold text-slate-200">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="mt-4 font-display font-bold text-ink leading-snug group-hover:text-primary-700 transition">{{ $area['title'] }}</h3>
                        @if (!empty($area['desc']))
                            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-3">{{ $area['desc'] }}</p>
                        @endif
                        <span class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-primary-700">
                            Detail
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    </button>
                @endforeach
            </div>

            <x-profil.detail-modal />
        </section>
    @else
        <section class="container-page py-16">
            <div class="card-padded max-w-2xl">
                <p class="heading-eyebrow">Belum ada data</p>
                <h2 class="mt-2 text-xl font-semibold text-primary-900">Area perubahan sedang disiapkan</h2>
                <p class="mt-2 text-muted">6 area perubahan Zona Integritas dapat ditambahkan melalui CMS (Profil → Reformasi Birokrasi).</p>
            </div>
        </section>
    @endif

    {{-- ════════════════════════════════════════════════════════════════
     RENJA ZI — link to the document
     ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100">
        <div class="container-page py-12 lg:py-14">
            <div class="relative bg-gradient-to-br from-primary-900 to-primary-700 rounded-2xl p-8 lg:p-10 text-white overflow-hidden">
                <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.05)" />
                <x-decor.dots class="-top-4 -right-4 w-40 h-40" color="rgb(34 211 238 / 0.30)" />
                <div class="relative grid md:grid-cols-12 gap-6 items-center">
                    <div class="md:col-span-8">
                        <p class="text-xs font-bold tracking-widest uppercase text-accent-400">Dokumen</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-display font-bold">Rencana Kerja Pembangunan Zona Integritas</h2>
                        <p class="mt-2 text-sm text-slate-200 leading-relaxed max-w-xl">Dokumen Renja ZI memuat program kerja, sasaran, dan target tiap area perubahan menuju predikat WBK/WBBM.</p>
                    </div>
                    <div class="md:col-span-4 md:text-right">
                        @if ($renjaUrl)
                            <a href="{{ $renjaUrl }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-white text-primary-900 hover:bg-slate-100 transition shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Buka Renja ZI
                            </a>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/80 text-xs font-medium px-4 py-2.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Dokumen segera tersedia
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     WBK & MENUJU WBBM — media dokumentasi pelaksanaan & penilaian (CMS)
     Deep-link dari submenu via #wbk / #wbbm.
     ════════════════════════════════════════════════════════════════ --}}
    @foreach ([['wbk', 'WBK', 'Wilayah Bebas dari Korupsi', $wbk], ['wbbm', 'Menuju WBBM', 'Wilayah Birokrasi Bersih dan Melayani', $wbbm]] as [$secId, $heading, $sub, $data])
        <section id="{{ $secId }}" class="container-page py-12 lg:py-16 scroll-mt-24">
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Zona Integritas · {{ $sub }}</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">{{ $heading }}</h2>
                <p class="mt-2 text-sm text-muted">{{ $data['body'] ?? ($sub.' — dokumentasi pelaksanaan dan penilaian.') }}</p>
            </div>

            @if (!empty($data['media']))
                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($data['media'] as $m)
                        @if ($m['is_image'])
                            <a href="{{ $m['url'] }}" target="_blank" rel="noopener" title="{{ $m['label'] }}"
                                class="group block aspect-[4/3] rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                <img src="{{ $m['url'] }}" alt="{{ $m['label'] }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                            </a>
                        @else
                            <a href="{{ $m['url'] }}" target="_blank" rel="noopener"
                                class="group flex flex-col justify-between aspect-[4/3] rounded-xl border border-slate-100 p-4 hover:border-primary-200 hover:bg-primary-50/50 transition">
                                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span class="text-xs font-semibold text-ink line-clamp-3">{{ $m['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="mt-6 bg-white border border-slate-100 rounded-2xl p-8 max-w-2xl">
                    <p class="text-muted">Media dokumentasi {{ $heading }} (pelaksanaan &amp; penilaian) sedang disiapkan dan dapat diatur melalui CMS — Profil → Reformasi Birokrasi → entri <strong>{{ $heading }}</strong>.</p>
                </div>
            @endif
        </section>
    @endforeach

    {{-- ════════════════════════════════════════════════════════════════
     NAVIGASI PROFIL
     ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-16 lg:py-20">
        <div class="bg-white border border-slate-100 rounded-2xl p-8">
            <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
            <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Halaman Profil Lainnya</h2>
            <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach ([['Struktur Organisasi', 'profil.struktur'], ['Tugas & Fungsi', 'profil.tugas-fungsi'], ['Maklumat Pelayanan', 'profil.maklumat'], ['Standar Pelayanan', 'profil.standar']] as [$label, $routeName])
                    <a href="{{ route($routeName) }}" class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:border-primary-200 hover:bg-primary-50/50 transition">
                        <span class="text-sm font-semibold text-ink group-hover:text-primary-700">{{ $label }}</span>
                        <svg class="w-4 h-4 text-muted group-hover:text-primary-700 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
