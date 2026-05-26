@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Standar Pelayanan DPMPTSP Kota Surabaya — daftar layanan sesuai SSW Alfa beserta persyaratan, alur, dasar hukum, dan dokumen resmi tiap tahun.'), 160))

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
                <span class="text-white font-medium">Standar Pelayanan</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Standar Pelayanan' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $intro ?? 'Standar Pelayanan setiap layanan DPMPTSP Kota Surabaya sesuai 14 komponen UU No. 25 Tahun 2009 tentang Pelayanan Publik.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                        {{ $services->count() }} layanan
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                        {{ $documents->count() }} dokumen tahunan
                    </span>
                </div>
            </div>
        </div>
        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     DOKUMEN RESMI PER TAHUN
     ════════════════════════════════════════════════════════════════ --}}
    @if ($documents->isNotEmpty())
        <section class="container-page py-12 lg:py-14">
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Dokumen Resmi</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Standar Pelayanan <span class="italic text-primary-700">per tahun</span></h2>
                <p class="mt-2 text-sm text-muted">Dokumen resmi Standar Pelayanan DPMPTSP yang mencakup seluruh layanan, diterbitkan setiap tahun.</p>
            </div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($documents as $doc)
                    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col">
                        <div class="flex items-center justify-between">
                            <span class="font-display font-extrabold text-3xl text-primary-700">{{ $doc->year }}</span>
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-700 grid place-items-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                        </div>
                        <p class="mt-2 text-sm font-medium text-ink leading-snug">{{ $doc->title ?? 'Standar Pelayanan '.$doc->year }}</p>
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            @if ($doc->file_url)
                                <a href="{{ $doc->file_url }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 hover:text-primary-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                                    Unduh Dokumen
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Segera tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ════════════════════════════════════════════════════════════════
     STANDAR PER LAYANAN — cards → detail modal (14 komponen)
     ════════════════════════════════════════════════════════════════ --}}
    @if ($services->isNotEmpty())
        <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden"
            x-data="{
                open: false,
                loading: false,
                sel: { name: '', components: [] },
                async load(id, name) {
                    this.sel = { name: name, components: [] };
                    this.open = true;
                    this.loading = true;
                    try {
                        const r = await fetch('{{ url('profil/standar-pelayanan') }}/' + id, { headers: { 'Accept': 'application/json' } });
                        if (r.ok) this.sel = await r.json();
                    } catch (e) {}
                    this.loading = false;
                }
            }"
            x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''">
            <x-decor.dots class="-bottom-10 -left-10 w-80 h-80 opacity-50" color="rgb(14 77 164 / 0.10)" />
            <div class="container-page py-16 lg:py-20 relative">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Standar per Layanan</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Telusuri layanan &amp; lihat <span class="italic text-primary-700">standarnya</span></h2>
                    <p class="mt-2 text-sm text-muted">Pilih kategori untuk membuka daftar layanan, lalu klik sebuah layanan untuk melihat persyaratan, alur, dasar hukum, durasi, kontak, retribusi, dan lainnya.</p>
                </div>

                <div class="mt-8 max-w-3xl space-y-3">
                    @forelse ($roots as $node)
                        @include('pages.profil.partials.sp-node', ['node' => $node, 'childrenMap' => $childrenMap, 'depth' => 0])
                    @empty
                        <p class="text-sm text-muted">Belum ada layanan.</p>
                    @endforelse
                </div>
            </div>

            {{-- ─── Service-standard detail modal ─── --}}
            <div x-cloak x-show="open" x-transition.opacity
                @keydown.escape.window="open = false"
                class="fixed inset-0 z-[60] bg-primary-950/80 backdrop-blur-sm grid place-items-center p-4"
                role="dialog" aria-modal="true" aria-label="Detail standar layanan">
                <div @click="open = false" class="absolute inset-0"></div>
                <div x-show="open" x-transition
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[88vh] overflow-auto overscroll-contain">
                    <div class="sticky top-0 bg-gradient-to-br from-primary-700 to-primary-900 text-white p-6 relative overflow-hidden z-10">
                        <x-decor.dots class="-top-4 -right-4 w-28 h-28" color="rgb(34 211 238 / 0.30)" />
                        <button type="button" @click="open = false"
                            class="absolute top-3 right-3 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 grid place-items-center transition" aria-label="Tutup">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <p class="relative text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Standar Pelayanan</p>
                        <h3 class="relative mt-1 text-xl font-display font-bold pr-10" x-text="sel.name"></h3>
                    </div>
                    <div class="p-6">
                        <div x-show="loading" class="py-10 text-center text-sm text-muted">
                            <svg class="w-5 h-5 mx-auto animate-spin text-primary-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            <span class="mt-2 inline-block">Memuat standar…</span>
                        </div>
                        <ol class="space-y-5" x-show="!loading && sel.components && sel.components.length">
                            <template x-for="(c, ci) in sel.components" :key="ci">
                                <li class="flex gap-3">
                                    <span class="mt-0.5 w-7 h-7 rounded-lg bg-primary-50 text-primary-700 grid place-items-center shrink-0 text-xs font-bold font-display" x-text="ci + 1"></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold tracking-wide uppercase text-primary-700" x-text="c.label"></p>
                                        <p class="mt-1 text-sm text-ink leading-relaxed whitespace-pre-line" x-text="c.content"></p>
                                    </div>
                                </li>
                            </template>
                        </ol>
                        <p class="text-sm text-muted" x-show="!loading && (!sel.components || sel.components.length === 0)">
                            Standar untuk layanan ini sedang disiapkan.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($services->isEmpty() && $documents->isEmpty())
        <section class="container-page py-16">
            <div class="card-padded max-w-2xl">
                <p class="heading-eyebrow">Belum ada data</p>
                <h2 class="mt-2 text-xl font-semibold text-primary-900">Standar Pelayanan sedang disiapkan</h2>
                <p class="mt-2 text-muted">Daftar layanan beserta 14 komponen standar dan dokumen resmi per tahun akan tersedia. Pengelola dapat menambahkannya melalui CMS (Profil → Standar Pelayanan).</p>
                <a href="{{ route('profil.index') }}" class="btn-ghost mt-5">← Kembali ke Profil</a>
            </div>
        </section>
    @endif

    {{-- ════════════════════════════════════════════════════════════════
     NAVIGASI PROFIL
     ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-16 lg:py-20">
        <div class="bg-white border border-slate-100 rounded-2xl p-8">
            <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
            <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Halaman Profil Lainnya</h2>
            <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach ([['SOP Pelayanan', 'profil.sop'], ['Maklumat Pelayanan', 'profil.maklumat'], ['Tugas & Fungsi', 'profil.tugas-fungsi'], ['Zona Integritas', 'profil.zi']] as [$label, $routeName])
                    <a href="{{ route($routeName) }}" class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:border-primary-200 hover:bg-primary-50/50 transition">
                        <span class="text-sm font-semibold text-ink group-hover:text-primary-700">{{ $label }}</span>
                        <svg class="w-4 h-4 text-muted group-hover:text-primary-700 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
