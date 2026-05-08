@extends('layouts.public')

@section('content')
    <section class="container-page py-16 lg:py-24">
        <p class="heading-eyebrow">{{ $section ?? 'Halaman' }}</p>
        <h1 class="mt-2 text-4xl md:text-5xl font-bold tracking-tight text-primary-900">{{ $pageTitle }}</h1>
        <p class="mt-4 max-w-2xl text-muted text-lg">
            Halaman ini sedang disiapkan dan akan segera tersedia. Sementara itu, Anda dapat mengakses informasi terkait melalui menu navigasi atau menghubungi DPMPTSP Kota Surabaya.
        </p>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('home') }}" class="btn-primary">← Kembali ke Beranda</a>
            <a href="{{ route('kontak.index') }}" class="btn-outline">Hubungi Kami</a>
        </div>
    </section>
@endsection
