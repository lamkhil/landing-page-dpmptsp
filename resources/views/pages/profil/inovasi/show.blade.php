@extends('layouts.public')

@section('title', $post->title)
@section('meta_description', $post->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? ''), 160))

@section('content')
    <x-page-header
        eyebrow="Inovasi DPMPTSP"
        :title="$post->title"
        :subtitle="$post->excerpt" />

    <section class="container-page py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-10">
            {{-- Main content --}}
            <div class="lg:col-span-8">
                {{-- Hero cover (kalau ada) ditarik ke atas body sebagai banner --}}
                @if ($post->cover_path)
                    <figure class="rounded-2xl overflow-hidden border border-slate-100 shadow-sm mb-8 bg-slate-50">
                        <img src="{{ asset('storage/'.$post->cover_path) }}"
                             alt="{{ $post->title }}"
                             class="w-full h-auto object-cover"
                             loading="eager">
                    </figure>
                @endif

                <article class="prose prose-slate max-w-none prose-headings:font-display prose-headings:text-primary-900 prose-a:text-primary-700 prose-img:rounded-xl">
                    {!! $post->body !!}
                </article>
            </div>

            {{-- Sidebar: kategori + nav + related --}}
            <aside class="lg:col-span-4 space-y-6">
                <div class="card-padded">
                    <p class="heading-eyebrow">Tentang Inovasi</p>
                    <dl class="mt-3 grid grid-cols-3 gap-3 text-sm">
                        <dt class="col-span-1 text-muted">Kategori</dt>
                        <dd class="col-span-2 font-medium">{{ $post->category?->name ?? '—' }}</dd>
                        <dt class="col-span-1 text-muted">Dipublikasi</dt>
                        <dd class="col-span-2 font-medium">{{ $post->published_at?->translatedFormat('d M Y') ?? '—' }}</dd>
                        @if ($post->is_featured)
                            <dt class="col-span-1 text-muted">Status</dt>
                            <dd class="col-span-2"><span class="chip">Inovasi Unggulan</span></dd>
                        @endif
                    </dl>
                </div>

                @if ($related->isNotEmpty())
                    <div class="card-padded">
                        <p class="heading-eyebrow">Inovasi Terkait</p>
                        <ul class="mt-3 divide-y divide-slate-100">
                            @foreach ($related as $r)
                                <li class="py-3 first:pt-0 last:pb-0">
                                    <a href="{{ route('profil.inovasi.show', $r->slug) }}" class="block group">
                                        <p class="font-semibold text-sm text-primary-900 group-hover:text-primary-700 transition">{{ $r->title }}</p>
                                        <p class="mt-1 text-xs text-muted line-clamp-2">{{ $r->excerpt }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <a href="{{ route('profil.inovasi.index') }}" class="btn-ghost">
                    ← Semua Inovasi
                </a>
            </aside>
        </div>
    </section>
@endsection
