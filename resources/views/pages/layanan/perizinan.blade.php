@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? 'Perizinan Berusaha berbasis risiko (OSS RBA) DPMPTSP Kota Surabaya — ajukan izin usaha melalui OSS.'), 160))

@php
    $risiko = [
        ['R', 'Risiko Rendah', 'Cukup Nomor Induk Berusaha (NIB) sebagai legalitas dan izin usaha.', 'from-emerald-500 to-emerald-600'],
        ['MR', 'Menengah Rendah', 'NIB + Sertifikat Standar berupa pernyataan mandiri pelaku usaha.', 'from-lime-500 to-lime-600'],
        ['MT', 'Menengah Tinggi', 'NIB + Sertifikat Standar yang diverifikasi pemerintah.', 'from-amber-500 to-amber-600'],
        ['T', 'Risiko Tinggi', 'NIB + Izin dan/atau Sertifikat Standar yang diverifikasi.', 'from-rose-500 to-rose-600'],
    ];
    $langkah = [
        ['Buat Akun & NIB', 'Daftar akun di OSS RBA dan terbitkan Nomor Induk Berusaha (NIB) sesuai KBLI usaha Anda.'],
        ['Pilih KBLI & Skala', 'Sistem menentukan tingkat risiko usaha berdasarkan KBLI dan skala usaha.'],
        ['Pemenuhan Standar', 'Lengkapi Sertifikat Standar / Izin sesuai tingkat risiko (untuk MR/MT/T).'],
        ['Verifikasi & Terbit', 'Tim teknis memverifikasi; izin terbit secara elektronik dan dapat dicetak mandiri.'],
    ];
@endphp

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
                <span class="text-white font-medium">Perizinan Berusaha</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">Layanan DPMPTSP</span>
                </span>
                <h1 class="mt-5 font-display font-extrabold text-3xl md:text-5xl tracking-tight leading-[1.1]">
                    {{ $post?->title ?? 'Perizinan Berusaha' }}
                </h1>
                <p class="mt-5 text-slate-200 text-lg leading-relaxed">
                    {{ $intro ?? 'Pengajuan izin usaha berbasis risiko (Risk-Based Approach) melalui Online Single Submission (OSS RBA) yang dikelola Kementerian Investasi/BKPM RI.' }}
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="https://oss.go.id" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-accent-500 text-primary-950 hover:bg-accent-400 transition shadow-lg shadow-accent-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        Ajukan via OSS
                    </a>
                    <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-white/10 border border-white/15 text-white hover:bg-white/20 transition">
                        SSW Alfa Surabaya
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
            <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Perizinan Berusaha Berbasis Risiko <span class="italic text-primary-700">(OSS RBA)</span></h2>
            <p class="mt-3 text-muted leading-relaxed">
                Sejak diberlakukannya <strong class="text-ink">UU Cipta Kerja</strong> dan <strong class="text-ink">PP No. 5 Tahun 2021</strong>, perizinan berusaha menggunakan pendekatan berbasis risiko melalui sistem <strong class="text-ink">OSS Berbasis Risiko</strong> yang dikelola Kementerian Investasi/BKPM RI. Tingkat risiko usaha menentukan jenis perizinan yang dibutuhkan.
            </p>

            <figure class="mt-6 rounded-2xl border-l-4 border-accent-500 bg-primary-50/50 p-6">
                <blockquote class="text-ink leading-relaxed italic">
                    &ldquo;Pelayanan perizinan dilaksanakan secara daring (<em>online system</em>) menggunakan aplikasi <strong>oss.go.id</strong> untuk perizinan berusaha dan <strong>sswalfa.surabaya.go.id</strong> untuk persyaratan dasar perizinan berusaha, perizinan non-berusaha, dan pelayanan non-perizinan. Tidak ada lagi proses perizinan yang dilakukan secara manual untuk menghindari adanya benturan atau konflik kepentingan.&rdquo;
                </blockquote>
                <figcaption class="mt-3 text-sm font-semibold text-primary-700">
                    — Lasidi, Kepala DPMPTSP Kota Surabaya
                    <span class="font-normal text-muted">(siaran pers Humas Pemkot Surabaya, 18 Juli 2025)</span>
                </figcaption>
            </figure>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ KATEGORI RISIKO ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100">
        <div class="container-page py-14 lg:py-16">
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Kategori Risiko</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Empat tingkat risiko usaha</h2>
                <p class="mt-2 text-sm text-muted">Sistem OSS menetapkan tingkat risiko berdasarkan KBLI dan skala usaha — menentukan perizinan yang wajib dipenuhi.</p>
            </div>
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($risiko as [$kode, $nama, $ket, $grad])
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 flex flex-col hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $grad }} text-white grid place-items-center font-display font-extrabold shrink-0">{{ $kode }}</div>
                        <h3 class="mt-4 font-display font-bold text-ink leading-snug">{{ $nama }}</h3>
                        <p class="mt-2 text-sm text-muted leading-relaxed">{{ $ket }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ KAMUS KBLI ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page pt-14 lg:pt-16">
        <a href="https://oss.go.id/id/kbli" target="_blank" rel="noopener"
            class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-white p-6 hover:shadow-lg hover:border-primary-200 transition">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-700 grid place-items-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <div>
                    <h3 class="font-display font-bold text-ink">Belum tahu kode KBLI usaha Anda?</h3>
                    <p class="mt-1 text-sm text-muted">Cek <strong class="text-ink">Klasifikasi Baku Lapangan Usaha Indonesia (KBLI)</strong> di Kamus KBLI OSS untuk menentukan kode &amp; tingkat risiko usaha Anda.</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold bg-primary-700 text-white group-hover:bg-primary-800 transition shrink-0 whitespace-nowrap">
                Buka Kamus KBLI
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
            </span>
        </a>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ CARA MENGAJUKAN ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-14 lg:py-16">
        <div class="max-w-2xl">
            <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Alur</p>
            <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Cara mengajukan</h2>
        </div>
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($langkah as $i => [$judul, $ket])
                <div class="relative bg-white rounded-2xl border border-slate-100 p-6">
                    <span class="text-3xl font-display font-extrabold text-slate-200">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-2 font-display font-bold text-ink leading-snug">{{ $judul }}</h3>
                    <p class="mt-2 text-sm text-muted leading-relaxed">{{ $ket }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ CTA OSS ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100">
        <div class="container-page py-12 lg:py-14">
            <div class="relative bg-gradient-to-br from-primary-900 to-primary-700 rounded-2xl p-8 lg:p-10 text-white overflow-hidden">
                <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.05)" />
                <x-decor.dots class="-top-4 -right-4 w-40 h-40" color="rgb(34 211 238 / 0.30)" />
                <div class="relative grid md:grid-cols-12 gap-6 items-center">
                    <div class="md:col-span-8">
                        <p class="text-xs font-bold tracking-widest uppercase text-accent-400">Mulai Sekarang</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-display font-bold">Ajukan Perizinan Berusaha</h2>
                        <p class="mt-2 text-sm text-slate-200 leading-relaxed max-w-xl">Perizinan berusaha kewenangan nasional diajukan melalui OSS RBA. Untuk perizinan kewenangan daerah Kota Surabaya, gunakan SSW Alfa.</p>
                    </div>
                    <div class="md:col-span-4 md:text-right flex flex-col sm:flex-row md:flex-col gap-2 md:items-end">
                        <a href="https://oss.go.id" target="_blank" rel="noopener"
                            class="inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-accent-500 text-primary-950 hover:bg-accent-400 transition shadow-lg">
                            Buka OSS RBA
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        </a>
                        <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener"
                            class="inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-white/10 border border-white/15 text-white hover:bg-white/20 transition">
                            SSW Alfa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════ NAVIGASI LAYANAN ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-16 lg:py-20">
        <div class="bg-white border border-slate-100 rounded-2xl p-8">
            <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Selengkapnya</p>
            <h2 class="mt-1 text-xl md:text-2xl font-display font-bold text-ink">Layanan Lainnya</h2>
            <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach ([['Perizinan Non-Berusaha', 'layanan.non-perizinan'], ['SOP Pelayanan', 'profil.sop'], ['Standar Pelayanan', 'profil.standar'], ['Tracking Permohonan', 'layanan.tracking']] as [$label, $routeName])
                    <a href="{{ route($routeName) }}" class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:border-primary-200 hover:bg-primary-50/50 transition">
                        <span class="text-sm font-semibold text-ink group-hover:text-primary-700">{{ $label }}</span>
                        <svg class="w-4 h-4 text-muted group-hover:text-primary-700 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
