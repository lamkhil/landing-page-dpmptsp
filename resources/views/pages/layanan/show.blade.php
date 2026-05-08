@extends('layouts.public')

@section('title', $post?->title ?? $fallbackTitle)
@section('meta_description', $post?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post?->excerpt ?? ''), 160))

@section('content')
    <x-page-header
        eyebrow="Layanan DPMPTSP"
        :title="$post?->title ?? $fallbackTitle"
        :subtitle="$post?->excerpt" />

    <section class="container-page py-12">
        @if ($post)
            <article class="prose prose-slate max-w-3xl prose-headings:font-display prose-headings:text-primary-900 prose-a:text-primary-700">
                {!! $post->body !!}
            </article>
        @else
            <div class="card-padded max-w-2xl">
                <p class="heading-eyebrow">Belum dipublikasi</p>
                <h2 class="mt-2 text-xl font-semibold text-primary-900">Konten sedang disiapkan</h2>
                <p class="mt-2 text-muted">Halaman layanan ini sedang disiapkan dan akan segera tersedia.</p>
                <div class="mt-5 flex flex-wrap gap-2">
                    <a href="{{ route('aplikasi.index') }}" class="btn-primary">Akses Aplikasi Publik</a>
                    <a href="{{ route('layanan.index') }}" class="btn-ghost">← Kembali ke Layanan</a>
                </div>
            </div>
        @endif
    </section>
@endsection
