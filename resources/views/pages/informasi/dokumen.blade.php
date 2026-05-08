@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        :title="$pageTitle"
        subtitle="Dokumen publik DPMPTSP — SOP, formulir, laporan, dan lainnya. Tersedia untuk diunduh masyarakat." />

    <section class="container-page py-12">
        @if ($paginator->isEmpty())
            <div class="card-padded text-center text-muted">Belum ada dokumen tersedia.</div>
        @else
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($paginator as $d)
                    <div class="card-padded">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-50 grid place-items-center text-primary-700 shrink-0">
                                📄
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold leading-snug">{{ $d->title }}</h3>
                                @if ($d->description)
                                    <p class="mt-1 text-xs text-muted line-clamp-2">{{ $d->description }}</p>
                                @endif
                                <p class="mt-2 text-xs text-muted">
                                    @if ($d->size_bytes) {{ number_format($d->size_bytes / 1024, 0) }} KB · @endif
                                    {{ $d->downloads_count }} unduhan
                                </p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/'.$d->file_path) }}" target="_blank" rel="noopener" class="btn-primary text-sm mt-4 w-full justify-center">Unduh</a>
                    </div>
                @endforeach
            </div>
            <div class="mt-10">{{ $paginator->links() }}</div>
        @endif
    </section>
@endsection
