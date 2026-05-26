@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Maklumat Pelayanan DPMPTSP Kota Surabaya — komitmen menyelenggarakan pelayanan sesuai standar.'), 160))

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
                <span class="text-white font-medium">Maklumat Pelayanan</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Maklumat Pelayanan' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $intro ?? 'Komitmen aparatur DPMPTSP Kota Surabaya untuk menyelenggarakan pelayanan publik sesuai standar yang telah ditetapkan.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        UU No. 25 Tahun 2009
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Zona Integritas WBK/WBBM
                    </span>
                </div>
            </div>
        </div>
        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>
    </section>

    @if (!$naskah && !count($komitmen))
        <section class="container-page py-16">
            <div class="card-padded max-w-2xl">
                <p class="heading-eyebrow">Belum ada data</p>
                <h2 class="mt-2 text-xl font-semibold text-primary-900">Konten sedang disiapkan</h2>
                <p class="mt-2 text-muted">Naskah Maklumat dan Komitmen Pelayanan dapat ditambahkan melalui CMS (Profil → Maklumat Pelayanan).</p>
                <a href="{{ route('profil.index') }}" class="btn-ghost mt-5">← Kembali ke Profil</a>
            </div>
        </section>
    @else

        {{-- ════════════════════════════════════════════════════════════════
         NASKAH MAKLUMAT — pledge centerpiece
         ════════════════════════════════════════════════════════════════ --}}
        @if ($naskah)
            <section class="container-page py-14 lg:py-20">
                <div class="relative max-w-4xl mx-auto bg-gradient-to-br from-primary-700 to-primary-900 rounded-3xl p-8 md:p-12 lg:p-16 text-white shadow-xl shadow-primary-950/20 overflow-hidden">
                    <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.04)" />
                    <x-decor.dots class="-top-6 -right-6 w-40 h-40" color="rgb(34 211 238 / 0.30)" />
                    <svg class="absolute top-6 left-6 w-20 h-20 text-accent-400/20" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/>
                    </svg>
                    <div class="relative text-center">
                        <p class="text-xs font-bold tracking-[0.2em] uppercase text-accent-400">Naskah Maklumat</p>
                        <blockquote class="mt-5">
                            <p class="font-display font-semibold text-xl md:text-2xl lg:text-3xl leading-snug">
                                &ldquo;{{ $naskah }}&rdquo;
                            </p>
                        </blockquote>
                        <div class="mt-7 inline-flex items-center gap-2 text-sm text-slate-300">
                            <span class="h-px w-8 bg-accent-400/50"></span>
                            DPMPTSP Kota Surabaya
                            <span class="h-px w-8 bg-accent-400/50"></span>
                        </div>
                        @if (count($naskahDocs))
                            <div class="mt-6 flex flex-wrap justify-center gap-2">
                                @foreach ($naskahDocs as $d)
                                    <a href="{{ $d['url'] }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5 hover:bg-white/20 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        {{ $d['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         KOMITMEN PELAYANAN — cards
         ════════════════════════════════════════════════════════════════ --}}
        @if (count($komitmen))
            @php
                $komitmenIcons = [
                    'M13 10V3L4 14h7v7l9-11h-7z', // cepat (bolt)
                    'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', // bebas pungli (lock)
                    'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', // SPM (check)
                    'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', // pengaduan (chat)
                    'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', // kepuasan (heart)
                ];
            @endphp
            <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden">
                <x-decor.dots class="-bottom-10 -left-10 w-80 h-80 opacity-50" color="rgb(14 77 164 / 0.10)" />
                <div class="container-page py-16 lg:py-20 relative">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Komitmen Pelayanan</p>
                        <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Janji layanan kami kepada <span class="italic text-primary-700">masyarakat</span></h2>
                        <p class="mt-2 text-sm text-muted">Komitmen yang kami pegang dalam setiap proses pelayanan perizinan dan penanaman modal.</p>
                    </div>
                    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($komitmen as $i => $k)
                            <div class="bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 grid place-items-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $komitmenIcons[$i % count($komitmenIcons)] }}" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-300 font-display">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <p class="mt-4 text-sm text-ink leading-relaxed">{{ $k['body'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         NASKAH RESMI (image) + NAVIGASI PROFIL
         ════════════════════════════════════════════════════════════════ --}}
        <section class="container-page py-16 lg:py-20">
            <div class="grid lg:grid-cols-2 gap-8 items-start">
                @if ($naskahImage)
                    <div x-data="{ zoom: false }"
                        x-effect="document.documentElement.style.overflow = zoom ? 'hidden' : ''">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Naskah Resmi</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Dokumen Maklumat Pelayanan</h2>
                        <figure class="mt-4 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <button type="button" @click="zoom = true"
                                class="group block w-full relative cursor-zoom-in bg-slate-50" aria-label="Perbesar naskah maklumat">
                                <img src="{{ $naskahImage }}" alt="Naskah Maklumat Pelayanan DPMPTSP Kota Surabaya"
                                    class="mx-auto w-auto max-w-full max-h-80 object-contain p-4" loading="lazy">
                                <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur text-primary-700 text-xs font-semibold px-3 py-1.5 shadow-sm opacity-0 group-hover:opacity-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m-3-3h6" /></svg>
                                    Perbesar
                                </span>
                            </button>
                        </figure>
                        <div x-cloak x-show="zoom" x-transition.opacity @keydown.escape.window="zoom = false"
                            class="fixed inset-0 z-[60] bg-primary-950/90 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Naskah maklumat diperbesar">
                            <button type="button" @click="zoom = false"
                                class="fixed top-4 right-4 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 text-white grid place-items-center transition" aria-label="Tutup">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="absolute inset-0 overflow-auto overscroll-contain" @click="zoom = false">
                                <div class="min-h-full min-w-full grid place-items-center p-4 md:p-10">
                                    <img src="{{ $naskahImage }}" @click.stop alt="Naskah Maklumat Pelayanan DPMPTSP Kota Surabaya"
                                        class="max-w-none rounded-xl bg-white shadow-2xl">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="{{ $naskahImage ? '' : 'lg:col-span-2' }} bg-white border border-slate-100 rounded-2xl p-8">
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
                    <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Jelajahi Profil DPMPTSP</h2>
                    <div class="mt-5 grid sm:grid-cols-2 gap-3">
                        @foreach ([['Standar Pelayanan', 'profil.standar'], ['SOP Pelayanan', 'profil.sop'], ['Zona Integritas', 'profil.zi'], ['Visi & Misi', 'profil.visi-misi']] as [$label, $routeName])
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
