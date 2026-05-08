@extends('layouts.public')

@section('title', $app->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($app->description ?? ''), 160))

@section('content')
    <section class="container-page py-12">
        <nav class="text-sm text-muted mb-6 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-primary-700">Beranda</a> ·
            <a href="{{ route('aplikasi.index') }}" class="hover:text-primary-700">Aplikasi Publik</a> ·
            <span class="text-ink">{{ $app->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8">
                <div class="card-padded">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-xl bg-primary-50 grid place-items-center text-primary-700 shrink-0">
                            @if ($app->icon_path)
                                <img src="{{ asset('storage/'.$app->icon_path) }}" alt="" class="w-10 h-10 object-contain">
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="heading-eyebrow">{{ $app->category?->name ?? 'Aplikasi Publik' }}</p>
                            <h1 class="mt-1 text-3xl font-bold text-primary-900">{{ $app->name }}</h1>
                            @if ($app->description)
                                <p class="mt-3 text-muted">{{ $app->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ $app->url }}"
                           @if($app->link_type === 'external') target="_blank" rel="noopener" @endif
                           class="btn-primary">
                            Buka {{ $app->name }}
                            @if ($app->link_type === 'external')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7m0 0v7m0-7L10 14M5 5h4M5 5v14h14v-4"/></svg>
                            @endif
                        </a>
                        <a href="{{ route('aplikasi.index') }}" class="btn-ghost">← Kembali</a>
                    </div>
                </div>

                @if ($app->thumbnail_path)
                    <img src="{{ asset('storage/'.$app->thumbnail_path) }}" alt=""
                         class="mt-6 w-full rounded-2xl border border-slate-100" loading="lazy">
                @endif
            </div>

            {{-- Sidebar info --}}
            <aside class="lg:col-span-4">
                <div class="card-padded">
                    <p class="heading-eyebrow">Detail</p>
                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-muted">Kategori</dt>
                            <dd class="font-medium text-right">{{ $app->category?->name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-muted">Tipe</dt>
                            <dd class="font-medium text-right capitalize">{{ $app->link_type }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-muted">Status</dt>
                            <dd class="font-medium text-right capitalize">{{ $app->status }}</dd>
                        </div>
                        @if ($app->published_at)
                            <div class="flex justify-between gap-3">
                                <dt class="text-muted">Dipublikasi</dt>
                                <dd class="font-medium text-right">{{ $app->published_at->translatedFormat('d M Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                @if ($related->isNotEmpty())
                    <div class="card-padded mt-5">
                        <p class="heading-eyebrow">Aplikasi Lainnya</p>
                        <ul class="mt-3 space-y-2 text-sm">
                            @foreach ($related as $r)
                                @if ($r->slug !== $app->slug)
                                    <li>
                                        <a href="{{ route('aplikasi.show', $r->slug) }}" class="hover:text-primary-700">
                                            {{ $r->name }}
                                            <span class="text-muted text-xs">· {{ $r->category_name ?? 'Aplikasi' }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
