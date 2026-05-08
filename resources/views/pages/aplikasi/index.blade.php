@extends('layouts.public')

@section('title', $pageTitle)
@section('meta_description', $seo?->meta_description ?? 'Akses cepat ke aplikasi publik DPMPTSP Surabaya.')

@section('content')
    <x-page-header
        eyebrow="DPMPTSP Surabaya"
        title="Aplikasi Publik"
        subtitle="Akses cepat ke seluruh aplikasi pelayanan publik DPMPTSP — perizinan, investasi, pengaduan, hingga open data." />

    {{-- Filter kategori --}}
    <section class="container-page py-10">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('aplikasi.index') }}"
               class="chip {{ ! $activeCat ? 'bg-primary-700 text-white' : '' }}">
                Semua
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('aplikasi.index', ['kategori' => $cat->slug]) }}"
                   class="chip {{ $activeCat === $cat->slug ? 'bg-primary-700 text-white' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured row --}}
    @if (! $activeCat && $featured->isNotEmpty())
        <section class="container-page pb-10">
            <p class="heading-eyebrow">Aplikasi Unggulan</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($featured as $app)
                    <a href="{{ $app->url }}"
                       @if($app->link_type === 'external') target="_blank" rel="noopener" @endif
                       class="card-padded block hover:shadow-md transition border-slate-100 hover:border-primary-200">
                        <div class="w-10 h-10 rounded-lg bg-primary-50 grid place-items-center text-primary-700">
                            @if ($app->icon_path)
                                <img src="{{ asset('storage/'.$app->icon_path) }}" alt="" class="w-6 h-6 object-contain">
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            @endif
                        </div>
                        <p class="mt-3 font-semibold">{{ $app->name }}</p>
                        <p class="mt-1 text-xs text-muted">{{ $app->category_name ?? 'Aplikasi' }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Grid all --}}
    <section class="container-page pb-20">
        <p class="heading-eyebrow">Semua Aplikasi</p>
        <h2 class="mt-1 text-2xl md:text-3xl font-bold text-primary-900">
            {{ $paginator->total() }} aplikasi {{ $activeCat ? 'pada kategori ini' : 'tersedia' }}
        </h2>

        @if ($paginator->isEmpty())
            <div class="mt-8 card-padded text-center text-muted">Belum ada aplikasi pada kategori ini.</div>
        @else
            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($paginator as $app)
                    <article class="card overflow-hidden hover:shadow-md transition">
                        @if ($app->thumbnail_path)
                            <img src="{{ asset('storage/'.$app->thumbnail_path) }}" alt="" loading="lazy" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-primary-50 to-primary-100"></div>
                        @endif
                        <div class="p-5">
                            <div class="flex items-center gap-2">
                                <span class="chip">{{ $app->category?->name ?? 'Aplikasi' }}</span>
                                @if ($app->is_featured)
                                    <span class="chip bg-amber-50 !text-amber-700">★ Featured</span>
                                @endif
                            </div>
                            <h3 class="mt-3 font-semibold text-lg leading-snug">{{ $app->name }}</h3>
                            @if ($app->description)
                                <p class="mt-1 text-sm text-muted line-clamp-3">{{ $app->description }}</p>
                            @endif
                            <div class="mt-4 flex items-center gap-2">
                                <a href="{{ route('aplikasi.show', $app->slug) }}" class="btn-ghost text-sm">Detail →</a>
                                <a href="{{ $app->url }}"
                                   @if($app->link_type === 'external') target="_blank" rel="noopener" @endif
                                   class="btn-primary text-sm">Buka Aplikasi</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $paginator->withQueryString()->links() }}</div>
        @endif
    </section>
@endsection
