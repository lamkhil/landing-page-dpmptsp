@extends('layouts.public')

@section('title', $pageTitle)
@section('meta_description', $seo?->meta_description ?? 'Dashboard statistik DPMPTSP Surabaya — investasi PMA/PMDN, perizinan, SLA, dan kepuasan masyarakat.')

@section('content')
    <x-page-header
        eyebrow="Dashboard"
        title="Statistik DPMPTSP"
        subtitle="Realisasi investasi, jumlah perizinan, SLA pelayanan, dan indeks kepuasan masyarakat — diperbarui berkala oleh DPMPTSP Kota Surabaya." />

    {{-- Counter cards --}}
    <section class="container-page py-12">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($counters as $c)
                <div class="card-padded" x-data="counter({{ (int) round($c->value) }})" x-intersect="start">
                    <p class="heading-eyebrow">{{ $c->unit ?? '' }}</p>
                    <p class="mt-1 text-3xl font-display font-bold text-primary-900" x-text="value.toLocaleString('id-ID')">0</p>
                    <p class="mt-1 text-sm text-muted">{{ $c->label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Charts --}}
    <section class="container-page pb-12">
        <p class="heading-eyebrow">Tren Tahunan</p>
        <h2 class="mt-1 text-2xl md:text-3xl font-bold text-primary-900">5 tahun terakhir</h2>

        <div class="mt-6 grid gap-5 lg:grid-cols-2">
            <x-charts.line-trend
                title="Investasi PMA"
                unit="{{ $groups['pma']['unit'] ?? null }}"
                :series="$series['pma']"
                color="#0E4DA4" />

            <x-charts.line-trend
                title="Investasi PMDN"
                unit="{{ $groups['pmdn']['unit'] ?? null }}"
                :series="$series['pmdn']"
                color="#0891b2" />

            <x-charts.line-trend
                title="Izin Diterbitkan"
                unit="{{ $groups['izin']['unit'] ?? null }}"
                :series="$series['izin']"
                color="#059669" />

            <x-charts.line-trend
                title="Indeks Kepuasan Masyarakat"
                unit="{{ $groups['ikm']['unit'] ?? null }}"
                :series="$series['ikm']"
                color="#d97706" />
        </div>
    </section>

    {{-- Sub-dashboards quick links --}}
    <section class="container-page pb-20">
        <div class="card-padded bg-gradient-to-br from-primary-50 to-white border-primary-100">
            <p class="heading-eyebrow">Dashboard Lainnya</p>
            <h2 class="mt-1 text-2xl font-bold text-primary-900">Jelajahi data per topik</h2>
            <div class="mt-5 grid gap-3 md:grid-cols-3">
                <a href="{{ route('statistik.investasi') }}" class="card-padded hover:shadow-md transition">📈 Dashboard Investasi</a>
                <a href="{{ route('statistik.perizinan') }}" class="card-padded hover:shadow-md transition">📄 Dashboard Perizinan</a>
                <a href="{{ route('statistik.kepuasan') }}" class="card-padded hover:shadow-md transition">⭐ Statistik Kepuasan</a>
            </div>
        </div>
    </section>
@endsection
