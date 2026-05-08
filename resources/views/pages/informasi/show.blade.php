@extends('layouts.public')

@section('title', $post->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->body ?? ''), 160))

@section('content')
    <article class="container-page py-12">
        <nav class="text-sm text-muted mb-6 flex items-center gap-2 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-primary-700">Beranda</a> ·
            <a href="{{ route($listRoute) }}" class="hover:text-primary-700">{{ ucfirst($section) }}</a> ·
            <span class="text-ink truncate">{{ $post->title }}</span>
        </nav>

        <div class="grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8">
                <p class="heading-eyebrow">{{ ucfirst($section) }}</p>
                <h1 class="mt-2 text-3xl md:text-4xl font-bold text-primary-900 leading-tight">{{ $post->title }}</h1>
                <p class="mt-3 text-sm text-muted flex items-center gap-3">
                    <span>📅 {{ $post->published_at?->translatedFormat('d F Y · H:i') }}</span>
                    @if ($post->author?->name)
                        <span>· ✍️ {{ $post->author->name }}</span>
                    @endif
                    <span>· 👁 {{ number_format($post->view_count) }}</span>
                </p>

                @if ($post->cover_path)
                    <img src="{{ asset('storage/'.$post->cover_path) }}" alt="" class="mt-6 w-full rounded-2xl border border-slate-100" loading="lazy">
                @endif

                <div class="mt-8 prose prose-slate max-w-none prose-headings:font-display prose-headings:text-primary-900 prose-a:text-primary-700">
                    {!! $post->body !!}
                </div>
            </div>

            <aside class="lg:col-span-4">
                @if ($related->isNotEmpty())
                    <div class="card-padded">
                        <p class="heading-eyebrow">{{ ucfirst($section) }} Lainnya</p>
                        <ul class="mt-3 space-y-3 text-sm">
                            @foreach ($related as $r)
                                <li class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                                    <a href="{{ route("informasi.{$section}.show", $r->slug) }}" class="font-medium hover:text-primary-700">{{ $r->title }}</a>
                                    <p class="text-xs text-muted">{{ $r->published_at?->translatedFormat('d M Y') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-padded mt-5">
                    <p class="heading-eyebrow">Bagikan</p>
                    <div class="mt-3 flex gap-2">
                        @php $url = urlencode(url()->current()); $title = urlencode($post->title); @endphp
                        <a href="https://wa.me/?text={{ $title }}%20{{ $url }}" target="_blank" rel="noopener" class="btn-outline text-sm">WhatsApp</a>
                        <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}" target="_blank" rel="noopener" class="btn-outline text-sm">Twitter</a>
                    </div>
                </div>
            </aside>
        </div>
    </article>
@endsection
