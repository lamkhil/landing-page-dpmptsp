@extends('layouts.public')

@section('title', $post->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->body ?? ''), 160))

@php
    $color  = $post->category?->color ?: '#0E4DA4';
    $cover  = fn ($p) => $p?->cover_path ? asset('storage/'.$p->cover_path) : null;
@endphp

@push('head')
<style>
    .ig-content { color:#334155; }
    .ig-content > p { margin:0 0 1rem; line-height:1.75; }
    .ig-content h3 { font-family:'Plus Jakarta Sans','Inter',sans-serif; color:#082c5e; font-size:1.15rem; font-weight:700; margin:1.75rem 0 1rem; }
    .ig-content ol { list-style:none; counter-reset:step; margin:0; padding:0; display:grid; gap:.7rem; }
    .ig-content ol > li { counter-increment:step; position:relative; padding:1rem 1.15rem 1rem 3.6rem; background:#fff; border:1px solid #e2e8f0; border-radius:.9rem; box-shadow:0 1px 2px rgb(15 23 42/.04); line-height:1.6; }
    .ig-content ol > li::before { content:counter(step); position:absolute; left:.85rem; top:.95rem; width:2rem; height:2rem; display:grid; place-items:center; border-radius:.6rem; background:var(--ig-accent,#0E4DA4); color:#fff; font-weight:800; font-size:.9rem; }
    .ig-content ol > li strong { color:#082c5e; }
    .ig-content blockquote { margin:1.5rem 0 0; padding:.85rem 1.1rem; background:#f1f5f9; border-left:4px solid var(--ig-accent,#0E4DA4); border-radius:.5rem; color:#0f172a; font-size:.95rem; }
    .ig-content a { color:var(--ig-accent,#0E4DA4); font-weight:600; text-decoration:underline; }
</style>
@endpush

@section('content')
    <article class="container-page py-10">
        {{-- Breadcrumb --}}
        <nav class="text-sm text-muted mb-6 flex items-center gap-2 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-primary-700">Beranda</a> ·
            <a href="{{ route('informasi.infografis.index') }}" class="hover:text-primary-700">Infografis</a> ·
            <span class="text-ink truncate">{{ $post->title }}</span>
        </nav>

        <div class="grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8">
                <div class="flex items-center gap-2">
                    <span class="heading-eyebrow">Infografis</span>
                    @if ($post->category?->name)
                        <span class="chip" style="background-color:{{ $color }}1a;color:{{ $color }}">{{ $post->category->name }}</span>
                    @endif
                </div>
                <h1 class="mt-2 text-3xl md:text-4xl font-bold text-primary-900 leading-tight">{{ $post->title }}</h1>
                <p class="mt-3 text-sm text-muted flex items-center gap-3 flex-wrap">
                    <span>📅 {{ $post->published_at?->translatedFormat('d F Y') }}</span>
                    <span>· 👁 {{ number_format($post->view_count, 0, ',', '.') }} dilihat</span>
                </p>

                {{-- Gambar infografis --}}
                @if ($cover($post))
                    <figure class="mt-6 overflow-hidden rounded-2xl border border-slate-100 shadow-sm">
                        <img src="{{ $cover($post) }}" alt="Infografis: {{ $post->title }}" class="w-full" loading="lazy">
                    </figure>
                @endif

                {{-- Konten --}}
                <div class="ig-content mt-8" style="--ig-accent:{{ $color }}">
                    {!! $post->body !!}
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-6">
                <div class="card-padded">
                    <p class="heading-eyebrow">Bagikan</p>
                    @php $url = urlencode(url()->current()); $title = urlencode($post->title); @endphp
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="https://wa.me/?text={{ $title }}%20{{ $url }}" target="_blank" rel="noopener" class="btn-outline text-sm">WhatsApp</a>
                        <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}" target="_blank" rel="noopener" class="btn-outline text-sm">Twitter / X</a>
                        @if ($cover($post))
                            <a href="{{ $cover($post) }}" download class="btn-ghost text-sm">Unduh Gambar</a>
                        @endif
                    </div>
                </div>

                @if ($related->isNotEmpty())
                    <div class="card-padded">
                        <p class="heading-eyebrow">Infografis Terkait</p>
                        <div class="mt-4 space-y-4">
                            @foreach ($related as $r)
                                <a href="{{ route('informasi.infografis.show', $r->slug) }}" class="flex gap-3 group">
                                    <span class="shrink-0 w-20 h-14 rounded-lg overflow-hidden bg-gradient-to-br from-primary-100 to-primary-50">
                                        @if ($cover($r))
                                            <img src="{{ $cover($r) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        @if ($r->category?->name)
                                            <span class="text-[11px] font-semibold text-primary-700">{{ $r->category->name }}</span>
                                        @endif
                                        <h4 class="text-sm font-semibold leading-snug line-clamp-2 group-hover:text-primary-700">{{ $r->title }}</h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card-padded bg-primary-700 text-white">
                    <p class="font-display font-bold text-lg">Butuh bantuan perizinan?</p>
                    <p class="mt-1 text-sm text-primary-100">Konsultasi gratis melalui Klinik Investasi atau ajukan langsung secara daring.</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('layanan.perizinan') }}" class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold bg-white text-primary-700 hover:bg-primary-50 transition">Ajukan Perizinan</a>
                        <a href="{{ route('informasi.infografis.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold border border-white/40 text-white hover:bg-white/10 transition">Infografis Lain</a>
                    </div>
                </div>
            </aside>
        </div>
    </article>
@endsection
