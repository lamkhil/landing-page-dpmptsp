@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Profil DPMPTSP"
        title="Pertanyaan yang Sering Diajukan"
        subtitle="Jawaban atas pertanyaan paling umum tentang layanan DPMPTSP Kota Surabaya." />

    <section class="container-page py-12">
        @forelse ($grouped as $catName => $items)
            <div class="mb-10">
                <h2 class="text-xl font-bold text-primary-900">{{ $catName }}</h2>
                <div class="mt-4 space-y-3">
                    @foreach ($items as $faq)
                        <details class="card-padded group" x-data>
                            <summary class="cursor-pointer flex items-center justify-between gap-3 list-none">
                                <span class="font-semibold">{{ $faq->question }}</span>
                                <svg class="w-5 h-5 text-muted transition group-open:rotate-180 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </summary>
                            <div class="mt-3 prose prose-sm prose-slate max-w-none">{!! $faq->body !!}</div>
                        </details>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="card-padded text-center text-muted">FAQ belum tersedia.</div>
        @endforelse
    </section>
@endsection
