@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Layanan Non-Perizinan & Perizinan Non-Berusaha DPMPTSP Kota Surabaya melalui SSW Alfa.'), 160))

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
                <span class="text-white font-medium">Perizinan Non-Berusaha</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Layanan DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Perizinan Non-Berusaha' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $intro ?? 'Perizinan non-berusaha kewenangan Kota Surabaya (izin di luar kegiatan usaha) beserta layanan non-perizinan — diajukan melalui SSW Alfa.' }}
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
            <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Izin di luar <span class="italic text-primary-700">kegiatan usaha</span></h2>
            <p class="mt-3 text-muted leading-relaxed">
                <strong class="text-ink">Perizinan non-berusaha</strong> adalah perizinan yang bukan untuk kegiatan usaha — misalnya pemakaian aset daerah, reklame, penelitian, atau pemanfaatan ruang. Diajukan melalui <strong class="text-ink">SSW Alfa</strong>, yang juga melayani <strong class="text-ink">persyaratan dasar perizinan berusaha</strong> serta <a href="{{ route('layanan.pelayanan-non-perizinan') }}" class="text-primary-700 font-semibold hover:underline">pelayanan non-perizinan</a>.
            </p>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ KATEGORI LAYANAN (SSW ALFA) ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100">
        <div class="container-page py-14 lg:py-16">
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Kategori Layanan</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Layanan per <span class="italic text-primary-700">bidang</span></h2>
                <p class="mt-2 text-sm text-muted">Perizinan non-berusaha dikelompokkan per bidang/dinas sesuai SSW Alfa. Klik kategori untuk melihat daftar layanan beserta persyaratan, alur, dan durasinya.</p>
            </div>

            @if (! empty($kategori) && count($kategori))
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($kategori as $kat)
                        <a href="{{ route('profil.standar') }}#{{ \Illuminate\Support\Str::slug($kat->name) }}"
                            class="group flex items-start justify-between gap-3 bg-white rounded-2xl border border-slate-100 p-5 hover:shadow-lg hover:-translate-y-0.5 hover:border-primary-200 transition-all">
                            <div class="min-w-0">
                                <h3 class="font-display font-semibold text-ink leading-snug group-hover:text-primary-700 transition">{{ $kat->name }}</h3>
                                @if ($kat->children_count)
                                    <p class="mt-1 text-xs text-muted">{{ $kat->children_count }} layanan</p>
                                @endif
                            </div>
                            <svg class="w-4 h-4 text-muted group-hover:text-primary-700 group-hover:translate-x-0.5 transition shrink-0 mt-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @endforeach
                </div>
                <p class="mt-6 text-sm text-muted">
                    Lihat seluruh layanan &amp; standarnya di <a href="{{ route('profil.standar') }}" class="text-primary-700 font-semibold hover:underline">Standar Pelayanan</a>, atau ajukan langsung via <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener" class="text-primary-700 font-semibold hover:underline">SSW Alfa</a>.
                </p>
            @else
                <div class="mt-6 bg-white border border-slate-100 rounded-2xl p-8 max-w-2xl">
                    <p class="text-muted">Daftar kategori layanan sedang disiapkan. Sementara itu, akses langsung melalui <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener" class="text-primary-700 font-semibold hover:underline">SSW Alfa</a>.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ PERIZINAN NON-BERUSAHA ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-14 lg:py-16">
        <div class="grid md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-7">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Perizinan Non-Berusaha</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Izin non-berusaha kewenangan daerah</h2>
                <p class="mt-3 text-muted leading-relaxed">
                    Perizinan yang bukan untuk kegiatan usaha (mis. izin pemakaian aset/gedung, izin penyelenggaraan kegiatan, dan layanan kelurahan) diajukan melalui <strong class="text-ink">SSW Alfa Surabaya</strong>. Persyaratan, alur, dan dasar hukum tiap layanan dapat dilihat pada halaman <a href="{{ route('profil.standar') }}" class="text-primary-700 font-semibold hover:underline">Standar Pelayanan</a>.
                </p>
            </div>
            <div class="md:col-span-5">
                <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener"
                    class="group block bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg hover:border-primary-200 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary-700 text-white grid place-items-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        </div>
                        <div>
                            <p class="font-display font-bold text-ink">SSW Alfa Surabaya</p>
                            <p class="text-xs text-muted">sswalfa.surabaya.go.id</p>
                        </div>
                    </div>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary-700">
                        Buka portal <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </span>
                </a>
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
                    @foreach ([['Perizinan Berusaha', 'layanan.perizinan'], ['Pelayanan Non-Perizinan', 'layanan.pelayanan-non-perizinan'], ['Standar Pelayanan', 'profil.standar'], ['Tracking Permohonan', 'layanan.tracking']] as [$label, $routeName])
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
