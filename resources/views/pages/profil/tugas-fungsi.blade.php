@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Tugas dan fungsi DPMPTSP Kota Surabaya di bidang penanaman modal dan pelayanan perizinan terpadu satu pintu.'), 160))

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
                <span class="text-white font-medium">Tugas &amp; Fungsi</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Tugas &amp; Fungsi' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $post?->excerpt ?? 'Pelaksanaan urusan pemerintahan bidang penanaman modal serta penyelenggaraan pelayanan perizinan terpadu satu pintu di Kota Surabaya.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('informasi.regulasi.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5 hover:bg-white/20 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Perwali No. 52 Tahun 2023
                    </a>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Pelayanan Terpadu Satu Pintu
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
                <p class="mt-2 text-muted">Halaman Tugas &amp; Fungsi sedang disiapkan dan akan segera tersedia.</p>
                <a href="{{ route('profil.index') }}" class="btn-ghost mt-5">← Kembali ke Profil</a>
            </div>
        </section>
    @else

        {{-- ════════════════════════════════════════════════════════════════
         TUGAS POKOK — statement centerpiece
         ════════════════════════════════════════════════════════════════ --}}
        @if ($tugasPokok)
            <section class="container-page py-14 lg:py-16">
                <div class="grid lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-4">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Tugas Pokok</p>
                        <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Mandat <span class="italic text-primary-700">organisasi</span></h2>
                        <p class="mt-3 text-sm text-muted leading-relaxed">Kewenangan utama DPM-PTSP Kota Surabaya sesuai dasar hukum yang berlaku.</p>
                    </div>
                    <div class="lg:col-span-8">
                        <div class="relative bg-gradient-to-br from-primary-700 to-primary-900 rounded-2xl p-8 md:p-10 text-white shadow-xl shadow-primary-950/20 overflow-hidden">
                            <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.04)" />
                            <x-decor.dots class="-top-4 -right-4 w-36 h-36" color="rgb(34 211 238 / 0.30)" />
                            <div class="relative">
                                <div class="w-12 h-12 rounded-xl bg-accent-500/20 grid place-items-center text-accent-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                </div>
                                <div class="mt-5 text-lg md:text-xl leading-relaxed text-slate-100">
                                    {{ $tugasPokok }}
                                </div>
                                @if (count($tugasDocs))
                                    <div class="mt-6 pt-5 border-t border-white/15">
                                        <p class="text-[11px] font-bold tracking-widest uppercase text-accent-400">Dasar Hukum</p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @foreach ($tugasDocs as $d)
                                                <a href="{{ $d['url'] }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5 hover:bg-white/20 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    {{ $d['label'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
         FUNGSI — numbered cards
         ════════════════════════════════════════════════════════════════ --}}
        @if (count($fungsi))
            @php
                $fungsiIcons = [
                    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', // policy doc
                    'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', // growth/promotion
                    'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', // service check
                    'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', // oversight shield
                    'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', // data
                    'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', // complaints chat
                    'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3m4-14h.01M11 7h2m-2 4h2m-2 4h2', // admin office
                ];
                $fungsiItems = [];
                foreach ($fungsi as $idx => $f) {
                    $fungsiItems[] = [
                        'eyebrow' => 'Fungsi DPM-PTSP',
                        'title'   => 'Fungsi '.str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                        'desc'    => $f['body'],
                        'docs'    => $f['docs'],
                    ];
                }
            @endphp
            <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden"
                x-data="{ open: false, i: 0, items: @js($fungsiItems) }"
                x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''">
                <x-decor.dots class="-bottom-10 -left-10 w-80 h-80 opacity-50" color="rgb(14 77 164 / 0.10)" />
                <div class="container-page py-16 lg:py-20 relative">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Fungsi</p>
                        <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">{{ count($fungsi) }} fungsi <span class="italic text-primary-700">DPM-PTSP</span></h2>
                        <p class="mt-2 text-sm text-muted">Penjabaran fungsi yang dijalankan dalam menyelenggarakan urusan penanaman modal dan pelayanan perizinan. Klik kartu untuk melihat detail dan dasar hukum.</p>
                    </div>
                    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($fungsi as $i => $f)
                            <button type="button" @click="i = {{ $i }}; open = true"
                                class="group text-left bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg hover:-translate-y-0.5 hover:border-primary-200 transition-all cursor-pointer">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 grid place-items-center shrink-0 group-hover:bg-primary-700 group-hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $fungsiIcons[$i % count($fungsiIcons)] }}" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-300 font-display">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <p class="mt-4 text-sm text-ink leading-relaxed line-clamp-3">{{ $f['body'] }}</p>
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
         EMPTY STATE — no structured tugas pokok / fungsi yet.
         ════════════════════════════════════════════════════════════════ --}}
        @if (!$tugasPokok && !count($fungsi))
            <section class="container-page py-14">
                <div class="card-padded max-w-2xl mx-auto">
                    <p class="heading-eyebrow">Belum ada data</p>
                    <h2 class="mt-2 text-xl font-semibold text-primary-900">Konten sedang disiapkan</h2>
                    <p class="mt-2 text-muted">Tugas Pokok dan Fungsi dapat ditambahkan melalui CMS (Profil → Visi, Misi, Tugas &amp; Fungsi).</p>
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
                        <p class="mt-3 text-sm text-muted leading-relaxed">Tugas dan fungsi DPM-PTSP ditetapkan melalui Peraturan Walikota Surabaya tentang Kedudukan, Susunan Organisasi, Tugas, Fungsi dan Tata Kerja DPM-PTSP.</p>
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
                        @foreach ([['Profil DPMPTSP', 'profil.index'], ['Visi & Misi', 'profil.visi-misi'], ['Struktur Organisasi', 'profil.struktur'], ['Reformasi Birokrasi', 'profil.reformasi']] as [$label, $routeName])
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
