@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        title="Agenda DPMPTSP"
        subtitle="Jadwal kegiatan, sosialisasi, dan acara DPMPTSP Kota Surabaya." />

    <section class="container-page py-12">
        @if ($agendas->isEmpty())
            <div class="card-padded text-center text-muted">Belum ada agenda terjadwal.</div>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($agendas as $a)
                    <article class="card-padded flex gap-4">
                        <div class="text-center shrink-0 w-16">
                            <p class="text-xs uppercase text-primary-700 font-semibold">{{ $a->starts_at->translatedFormat('M Y') }}</p>
                            <p class="text-3xl font-bold leading-none mt-1">{{ $a->starts_at->format('d') }}</p>
                            <p class="text-xs text-muted mt-1">{{ $a->starts_at->translatedFormat('H:i') }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold leading-snug">{{ $a->title }}</h3>
                            @if ($a->location)
                                <p class="mt-1 text-xs text-muted">📍 {{ $a->location }}</p>
                            @endif
                            @if ($a->organizer)
                                <p class="text-xs text-muted">🏢 {{ $a->organizer }}</p>
                            @endif
                            @if ($a->ends_at)
                                <p class="text-xs text-muted">⏱ s/d {{ $a->ends_at->translatedFormat('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-10">{{ $agendas->links() }}</div>
        @endif
    </section>
@endsection
