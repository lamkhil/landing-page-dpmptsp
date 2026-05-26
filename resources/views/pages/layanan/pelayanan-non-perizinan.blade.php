@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Pelayanan Non-Perizinan DPMPTSP Kota Surabaya — surat keterangan, rekomendasi, dan layanan administrasi melalui SSW Alfa.'), 160))

@section('content')

    {{-- ════════════════════════════════════════════════════════════════ HERO ════════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white overflow-hidden">
        <x-decor.grid-lines class="opacity-50" color="rgb(255 255 255 / 0.04)" />
        <x-decor.dots class="top-8 right-8 w-64 h-64 opacity-60" color="rgb(34 211 238 / 0.25)" />
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-accent-500/10 blur-3xl"></div>

        <div class="container-page py-14 md:py-20 relative">
            <nav class="flex items-center gap-2 text-sm text-slate-300" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span class="text-white/30">/</span>
                <a href="{{ route('layanan.index') }}" class="hover:text-white">Layanan</a>
                <span class="text-white/30">/</span>
                <span class="text-white font-medium">Pelayanan Non-Perizinan</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Layanan DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Pelayanan Non-Perizinan' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $intro ?? 'Layanan non-perizinan — surat keterangan, rekomendasi, dan administrasi penunjang di luar perizinan — diajukan secara daring melalui SSW Alfa.' }}
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-accent-500 text-primary-950 hover:bg-accent-400 transition shadow-lg shadow-accent-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        Akses SSW Alfa
                    </a>
                    <a href="{{ route('kontak.index') }}"
                        class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-white/10 border border-white/15 text-white hover:bg-white/20 transition">
                        Tatap Muka di MPP Siola
                    </a>
                </div>
            </div>
        </div>
        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ TENTANG ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-14 lg:py-16">
        <div class="max-w-3xl">
            <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Tentang</p>
            <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Layanan <span class="italic text-primary-700">non-perizinan</span></h2>
            <p class="mt-3 text-muted leading-relaxed">
                Pelayanan non-perizinan mencakup penerbitan <strong class="text-ink">surat keterangan, rekomendasi, dan layanan administrasi</strong> yang bukan merupakan izin. Seluruhnya diajukan secara daring melalui <strong class="text-ink">SSW Alfa</strong> atau tatap muka di MPP Siola. Persyaratan & alur tiap layanan tersedia pada halaman <a href="{{ route('profil.standar') }}" class="text-primary-700 font-semibold hover:underline">Standar Pelayanan</a>.
            </p>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ DAFTAR LAYANAN NON-PERIZINAN (filter dari data SSW) ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100">
        <div class="container-page py-14 lg:py-16">
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Daftar Layanan</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Layanan non-perizinan</h2>
                <p class="mt-2 text-sm text-muted">Surat keterangan, rekomendasi, dan layanan administrasi (di luar izin) — diajukan melalui SSW Alfa.</p>
            </div>

            @if (! empty($layanan) && count($layanan))
                <ul class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($layanan as $svc)
                        <li class="flex items-start gap-3 bg-white rounded-xl border border-slate-100 p-4">
                            <svg class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span class="text-sm text-ink leading-snug">{{ $svc->name }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-6 text-sm text-muted">
                    {{ count($layanan) }} layanan non-perizinan. Lihat persyaratan & alur tiap layanan di
                    <a href="{{ route('profil.standar') }}" class="text-primary-700 font-semibold hover:underline">Standar Pelayanan</a>,
                    atau ajukan langsung via <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener" class="text-primary-700 font-semibold hover:underline">SSW Alfa</a>.
                </p>
            @else
                <div class="mt-6 bg-white border border-slate-100 rounded-2xl p-8 max-w-2xl">
                    <p class="text-muted">Daftar layanan non-perizinan sedang disiapkan. Sementara itu, akses langsung melalui <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener" class="text-primary-700 font-semibold hover:underline">SSW Alfa</a>.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ CTA SSW ALFA ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-12 lg:py-14">
        <div class="relative bg-gradient-to-br from-primary-900 to-primary-700 rounded-2xl p-8 lg:p-10 text-white overflow-hidden">
            <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.05)" />
            <x-decor.dots class="-top-4 -right-4 w-40 h-40" color="rgb(34 211 238 / 0.30)" />
            <div class="relative grid md:grid-cols-12 gap-6 items-center">
                <div class="md:col-span-8">
                    <p class="text-xs font-bold tracking-widest uppercase text-accent-400">Daring</p>
                    <h2 class="mt-1 text-xl md:text-2xl font-display font-bold">Ajukan melalui SSW Alfa</h2>
                    <p class="mt-2 text-sm text-slate-200 leading-relaxed max-w-xl">Seluruh pelayanan non-perizinan diajukan secara online melalui SSW Alfa — tidak ada lagi proses manual.</p>
                </div>
                <div class="md:col-span-4 md:text-right">
                    <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener"
                        class="inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-accent-500 text-primary-950 hover:bg-accent-400 transition shadow-lg">
                        Buka SSW Alfa
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ NAVIGASI LAYANAN ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-t border-slate-100">
        <div class="container-page py-16 lg:py-20">
            <div class="bg-white border border-slate-100 rounded-2xl p-8">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
                <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Layanan Lainnya</h2>
                <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach ([['Perizinan Berusaha', 'layanan.perizinan'], ['Perizinan Non-Berusaha', 'layanan.non-perizinan'], ['Standar Pelayanan', 'profil.standar'], ['Tracking Permohonan', 'layanan.tracking']] as [$label, $routeName])
                        <a href="{{ route($routeName) }}" class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:border-primary-200 hover:bg-primary-50/50 transition">
                            <span class="text-sm font-semibold text-ink group-hover:text-primary-700">{{ $label }}</span>
                            <svg class="w-4 h-4 text-muted group-hover:text-primary-700 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
