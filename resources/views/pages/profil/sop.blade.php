@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Standar Operasional Prosedur (SOP) pelayanan DPMPTSP Kota Surabaya — unduh dokumen SOP per kategori.'), 160))

@php
    // Normalize categories + an "uncategorized" bucket into one renderable list.
    $groups = $categories->map(fn ($c) => [
        'id'   => (string) $c->id,
        'name' => $c->name,
        'desc' => $c->description,
        'sops' => $c->sops,
    ]);
    if ($uncategorized->isNotEmpty()) {
        $groups = $groups->push(['id' => 'lainnya', 'name' => 'SOP Lainnya', 'desc' => null, 'sops' => $uncategorized]);
    }
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
                <span class="text-white font-medium">SOP Pelayanan</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Profil DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'SOP Pelayanan' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $intro ?? 'Standar Operasional Prosedur (SOP) yang mengatur alur, persyaratan, dan jangka waktu setiap layanan DPMPTSP Kota Surabaya.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                        {{ $totalSop }} dokumen SOP
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z" /></svg>
                        {{ $categories->count() }} kategori
                    </span>
                </div>
            </div>
        </div>
        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>
    </section>

    @if ($groups->isEmpty())
        {{-- ─── Empty state ─── --}}
        <section class="container-page py-16">
            <div class="card-padded max-w-2xl">
                <p class="heading-eyebrow">Belum ada SOP</p>
                <h2 class="mt-2 text-xl font-semibold text-primary-900">Dokumen sedang disiapkan</h2>
                <p class="mt-2 text-muted">Daftar SOP beserta dokumen yang dapat diunduh akan segera tersedia. Pengelola dapat menambahkannya melalui CMS (Profil → SOP Pelayanan &amp; Kategori SOP).</p>
                <a href="{{ route('profil.index') }}" class="btn-ghost mt-5">← Kembali ke Profil</a>
            </div>
        </section>
    @else
        <section class="container-page py-12 lg:py-16"
            x-data="{ active: 'all', open: false, sel: { title: '', category: '', desc: '', years: [] }, items: @js($itemsById) }"
            x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''">
            {{-- Filter chips --}}
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="active = 'all'"
                    :class="active === 'all' ? 'bg-primary-700 text-white border-primary-700' : 'bg-white text-ink border-slate-200 hover:border-primary-300 hover:text-primary-700'"
                    class="rounded-full px-4 py-2 text-sm font-semibold border transition">
                    Semua <span class="opacity-70">({{ $totalSop }})</span>
                </button>
                @foreach ($groups as $g)
                    <button type="button" @click="active = '{{ $g['id'] }}'"
                        :class="active === '{{ $g['id'] }}' ? 'bg-primary-700 text-white border-primary-700' : 'bg-white text-ink border-slate-200 hover:border-primary-300 hover:text-primary-700'"
                        class="rounded-full px-4 py-2 text-sm font-semibold border transition">
                        {{ $g['name'] }} <span class="opacity-70">({{ $g['sops']->count() }})</span>
                    </button>
                @endforeach
            </div>

            {{-- Category sections --}}
            <div class="mt-8 space-y-12">
                @foreach ($groups as $g)
                    @unless ($loop->first)
                        {{-- Divider between categories (only in the "Semua" view) --}}
                        <div x-show="active === 'all'" class="flex items-center gap-3" aria-hidden="true">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>
                    @endunless
                    <div x-show="active === 'all' || active === '{{ $g['id'] }}'" x-transition.opacity>
                        <div class="flex items-end justify-between gap-3 flex-wrap">
                            <div>
                                <h2 class="text-xl md:text-2xl font-display font-bold text-ink">{{ $g['name'] }}</h2>
                                @if ($g['desc'])
                                    <p class="mt-1 text-sm text-muted max-w-xl">{{ $g['desc'] }}</p>
                                @endif
                            </div>
                            <span class="chip">{{ $g['sops']->count() }} dokumen</span>
                        </div>

                        @if ($g['sops']->isEmpty())
                            <p class="mt-4 text-sm text-muted">Belum ada dokumen pada kategori ini.</p>
                        @else
                            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($g['sops'] as $sop)
                                    @php $yearCount = $sop->files->count(); @endphp
                                    <button type="button" @click="sel = items[{{ $sop->id }}]; open = true"
                                        class="group text-left bg-white rounded-2xl border border-slate-100 p-5 flex flex-col hover:shadow-lg hover:-translate-y-0.5 hover:border-primary-200 transition-all cursor-pointer">
                                        <div class="flex items-start gap-3">
                                            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 grid place-items-center shrink-0 group-hover:bg-primary-700 group-hover:text-white transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="chip">{{ $g['name'] }}</span>
                                                <h3 class="mt-1.5 font-semibold text-ink leading-snug group-hover:text-primary-700 transition">{{ $sop->title }}</h3>
                                                @if ($sop->doc_number)
                                                    <p class="text-xs font-mono text-muted mt-0.5">{{ $sop->doc_number }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($sop->description)
                                            <p class="mt-3 text-sm text-muted leading-relaxed line-clamp-2">{{ $sop->description }}</p>
                                        @endif
                                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                            @if ($yearCount)
                                                <span class="chip">{{ $yearCount }} versi tahun</span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    Segera tersedia
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-primary-700">
                                                Pilih tahun
                                                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                            </span>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- ─── Year chooser modal ─── --}}
            <div x-cloak x-show="open" x-transition.opacity
                @keydown.escape.window="open = false"
                class="fixed inset-0 z-[60] bg-primary-950/80 backdrop-blur-sm grid place-items-center p-4"
                role="dialog" aria-modal="true" aria-label="Pilih tahun dokumen SOP">
                <div @click="open = false" class="absolute inset-0"></div>
                <div x-show="open" x-transition
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-auto overscroll-contain">
                    <div class="bg-gradient-to-br from-primary-700 to-primary-900 text-white p-6 relative overflow-hidden">
                        <x-decor.dots class="-top-4 -right-4 w-28 h-28" color="rgb(34 211 238 / 0.30)" />
                        <button type="button" @click="open = false"
                            class="absolute top-3 right-3 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 grid place-items-center transition" aria-label="Tutup">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <p class="relative text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400" x-text="sel.category"></p>
                        <h3 class="relative mt-1 text-xl font-display font-bold pr-10" x-text="sel.title"></h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <p class="text-sm text-muted leading-relaxed" x-show="sel.desc" x-text="sel.desc"></p>

                        <div>
                            <p class="heading-eyebrow">Pilih Tahun Dokumen</p>
                            <div class="mt-3 grid sm:grid-cols-2 gap-3" x-show="sel.years && sel.years.length">
                                <template x-for="(f, fi) in sel.years" :key="fi">
                                    <div>
                                        {{-- Year with an uploaded file → download; otherwise show "segera tersedia" --}}
                                        <template x-if="f.url">
                                            <a :href="f.url" target="_blank" rel="noopener"
                                                class="group flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:border-primary-300 hover:bg-primary-50/50 transition">
                                                <span class="inline-flex items-center gap-2 font-semibold text-ink">
                                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    <span>SOP <span x-text="f.year"></span></span>
                                                </span>
                                                <svg class="w-4 h-4 text-muted group-hover:text-primary-700 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                                            </a>
                                        </template>
                                        <template x-if="!f.url">
                                            <div class="flex items-center justify-between gap-3 rounded-xl border border-dashed border-slate-200 px-4 py-3 text-slate-400">
                                                <span class="inline-flex items-center gap-2 font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    <span>SOP <span x-text="f.year"></span></span>
                                                </span>
                                                <span class="text-[11px]">segera tersedia</span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            <p class="mt-3 text-sm text-muted" x-show="!sel.years || sel.years.length === 0">
                                Dokumen untuk SOP ini sedang disiapkan dan akan segera tersedia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ════════════════════════════════════════════════════════════════
     NAVIGASI PROFIL
     ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page pb-16 lg:pb-20">
        <div class="bg-white border border-slate-100 rounded-2xl p-8">
            <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
            <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Halaman Profil Lainnya</h2>
            <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach ([['Standar Pelayanan', 'profil.standar'], ['Maklumat Pelayanan', 'profil.maklumat'], ['Tugas & Fungsi', 'profil.tugas-fungsi'], ['Struktur Organisasi', 'profil.struktur']] as [$label, $routeName])
                    <a href="{{ route($routeName) }}" class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:border-primary-200 hover:bg-primary-50/50 transition">
                        <span class="text-sm font-semibold text-ink group-hover:text-primary-700">{{ $label }}</span>
                        <svg class="w-4 h-4 text-muted group-hover:text-primary-700 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
