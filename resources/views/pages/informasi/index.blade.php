@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="DPMPTSP"
        title="Informasi Publik"
        subtitle="Berita, pengumuman, agenda, regulasi, dan dokumen publik DPMPTSP Kota Surabaya." />

    <section class="container-page py-12 grid lg:grid-cols-3 gap-8">
        {{-- Berita --}}
        <div class="lg:col-span-2">
            <div class="flex items-end justify-between">
                <h2 class="text-2xl font-bold text-primary-900">Berita Terbaru</h2>
                <a href="{{ route('informasi.berita.index') }}" class="btn-ghost text-sm">Semua →</a>
            </div>
            <div class="mt-4 grid sm:grid-cols-2 gap-4">
                @forelse ($latestNews as $n)
                    <article class="card overflow-hidden">
                        @if ($n->cover_path)
                            <img src="{{ $n->cover_url }}" alt="" class="w-full h-40 object-cover" loading="lazy">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-primary-50 to-primary-100"></div>
                        @endif
                        <div class="p-4">
                            <p class="text-xs text-muted">{{ $n->published_at?->translatedFormat('d F Y') }}</p>
                            <h3 class="mt-1 font-semibold leading-snug">
                                <a href="{{ route('informasi.berita.show', $n->slug) }}" class="hover:text-primary-700">{{ $n->title }}</a>
                            </h3>
                        </div>
                    </article>
                @empty
                    <p class="text-muted col-span-2">Belum ada berita.</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar: Pengumuman + Agenda --}}
        <aside class="space-y-6">
            <div class="card-padded">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-primary-900">Pengumuman</h2>
                    <a href="{{ route('informasi.pengumuman.index') }}" class="text-xs text-primary-700 hover:underline">Semua →</a>
                </div>
                <ul class="mt-3 space-y-3 text-sm">
                    @forelse ($latestAnnounce as $p)
                        <li class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                            <a href="{{ route('informasi.pengumuman.show', $p->slug) }}" class="hover:text-primary-700 font-medium">{{ $p->title }}</a>
                            <p class="text-xs text-muted">{{ $p->published_at?->translatedFormat('d M Y') }}</p>
                        </li>
                    @empty
                        <li class="text-muted text-sm">Belum ada pengumuman.</li>
                    @endforelse
                </ul>
            </div>

            <div class="card-padded">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-primary-900">Agenda</h2>
                    <a href="{{ route('informasi.agenda.index') }}" class="text-xs text-primary-700 hover:underline">Semua →</a>
                </div>
                <ul class="mt-3 space-y-3 text-sm">
                    @forelse ($upcomingAgenda as $a)
                        <li class="flex gap-3 border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                            <div class="text-center shrink-0">
                                <p class="text-xs uppercase text-primary-700 font-semibold">{{ $a->starts_at->translatedFormat('M') }}</p>
                                <p class="text-xl font-bold leading-none">{{ $a->starts_at->format('d') }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium leading-snug">{{ $a->title }}</p>
                                @if ($a->location)
                                    <p class="text-xs text-muted truncate">📍 {{ $a->location }}</p>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="text-muted text-sm">Belum ada agenda mendatang.</li>
                    @endforelse
                </ul>
            </div>
        </aside>
    </section>

    {{-- Quick links --}}
    <section class="container-page pb-20">
        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('informasi.regulasi.index') }}" class="card-padded hover:shadow-md transition">
                <p class="heading-eyebrow">Regulasi</p>
                <h3 class="mt-1 font-semibold">Perda, Perwali, SK</h3>
                <p class="mt-1 text-sm text-muted">Daftar regulasi yang menjadi dasar pelayanan DPMPTSP.</p>
            </a>
            <a href="{{ route('informasi.dokumen.index') }}" class="card-padded hover:shadow-md transition">
                <p class="heading-eyebrow">Dokumen</p>
                <h3 class="mt-1 font-semibold">Download Center</h3>
                <p class="mt-1 text-sm text-muted">SOP, formulir, laporan, dan dokumen publik lainnya.</p>
            </a>
            <a href="{{ route('informasi.infografis.index') }}" class="card-padded hover:shadow-md transition">
                <p class="heading-eyebrow">Infografis</p>
                <h3 class="mt-1 font-semibold">Visualisasi Layanan</h3>
                <p class="mt-1 text-sm text-muted">Infografis pelayanan publik DPMPTSP.</p>
            </a>
        </div>
    </section>
@endsection
