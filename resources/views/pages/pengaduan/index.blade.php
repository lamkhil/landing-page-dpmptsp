@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="DPMPTSP Surabaya"
        title="Pengaduan & Aspirasi"
        subtitle="Sampaikan keluhan, saran, atau aspirasi Anda atas pelayanan publik DPMPTSP. Tindak lanjut dapat dipantau secara online." />

    <section class="container-page py-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('pengaduan.lapor') }}" class="card-padded hover:shadow-md transition border-slate-100 hover:border-primary-200">
            <div class="w-10 h-10 rounded-lg bg-primary-50 grid place-items-center text-primary-700">📝</div>
            <h3 class="mt-3 font-semibold text-lg">Lapor Pengaduan</h3>
            <p class="mt-1 text-sm text-muted">Kirim pengaduan baru dan dapatkan nomor tiket.</p>
        </a>
        <a href="{{ route('pengaduan.tracking') }}" class="card-padded hover:shadow-md transition border-slate-100 hover:border-primary-200">
            <div class="w-10 h-10 rounded-lg bg-primary-50 grid place-items-center text-primary-700">🔍</div>
            <h3 class="mt-3 font-semibold text-lg">Tracking Pengaduan</h3>
            <p class="mt-1 text-sm text-muted">Cek status pengaduan via nomor tiket.</p>
        </a>
        <a href="{{ route('pengaduan.sp4n') }}" class="card-padded hover:shadow-md transition border-slate-100 hover:border-primary-200">
            <div class="w-10 h-10 rounded-lg bg-primary-50 grid place-items-center text-primary-700">🇮🇩</div>
            <h3 class="mt-3 font-semibold text-lg">SP4N LAPOR</h3>
            <p class="mt-1 text-sm text-muted">Sistem pengaduan nasional terintegrasi.</p>
        </a>
        <a href="{{ route('pengaduan.wbs') }}" class="card-padded hover:shadow-md transition border-slate-100 hover:border-primary-200">
            <div class="w-10 h-10 rounded-lg bg-primary-50 grid place-items-center text-primary-700">🛡️</div>
            <h3 class="mt-3 font-semibold text-lg">Whistleblowing</h3>
            <p class="mt-1 text-sm text-muted">Pelaporan dugaan pelanggaran integritas.</p>
        </a>
    </section>

    <section class="container-page pb-20">
        <div class="card-padded bg-gradient-to-br from-primary-50 to-white border-primary-100">
            <p class="heading-eyebrow">Komitmen Kami</p>
            <h2 class="mt-1 text-2xl md:text-3xl font-bold text-primary-900">Pengaduan ditindaklanjuti, identitas pelapor dilindungi.</h2>
            <p class="mt-3 text-muted max-w-2xl">DPMPTSP Surabaya menjamin tindak lanjut pengaduan sesuai SOP, dengan kerahasiaan identitas pelapor sebagai bagian dari komitmen WBK/WBBM.</p>
            <a href="{{ route('pengaduan.lapor') }}" class="btn-primary mt-5">Buat Pengaduan</a>
        </div>
    </section>
@endsection
