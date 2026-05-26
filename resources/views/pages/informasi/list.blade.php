@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        :title="$pageTitle"
        subtitle="Daftar {{ strtolower($pageTitle) }} terbaru dari DPMPTSP Kota Surabaya." />

    <section class="container-page py-10">
        <form method="get" class="card-padded flex items-center gap-2">
            <input type="search" name="q" value="{{ $searchTerm ?? '' }}"
                   placeholder="Cari judul..."
                   class="flex-1 px-4 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
            <button type="submit" class="btn-primary">Cari</button>
            @if ($searchTerm ?? null)
                <a href="{{ url()->current() }}" class="btn-ghost">Reset</a>
            @endif
        </form>
    </section>

    <section class="container-page pb-20">
        @if ($paginator->isEmpty())
            <div class="card-padded text-center text-muted">Tidak ada hasil ditemukan.</div>
        @else
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($paginator as $p)
                    <article class="card overflow-hidden hover:shadow-md transition">
                        @if ($p->cover_path)
                            <img src="{{ $p->cover_url }}" alt="" class="w-full h-40 object-cover" loading="lazy">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-primary-50 to-primary-100"></div>
                        @endif
                        <div class="p-5">
                            <p class="text-xs text-muted">{{ $p->published_at?->translatedFormat('d F Y') }}</p>
                            <h3 class="mt-1 font-semibold leading-snug">
                                <a href="{{ route($detailRouteName, $p->slug) }}" class="hover:text-primary-700">{{ $p->title }}</a>
                            </h3>
                            @if ($p->excerpt)
                                <p class="mt-2 text-sm text-muted line-clamp-3">{{ $p->excerpt }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $paginator->withQueryString()->links() }}</div>
        @endif
    </section>
@endsection
