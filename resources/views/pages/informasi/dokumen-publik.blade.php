@extends('layouts.public')

@section('title', $pageTitle)

@php
    // Inline icon set — keyed by category 'icon'. Kept here so the hub stays
    // self-contained (no extra component dependency).
    $icons = [
        'scale'    => 'M12 3v18M5 7l-3 6a3 3 0 006 0L5 7zm14 0l-3 6a3 3 0 006 0l-3-6zM4 7h16M8 21h8',
        'chart'    => 'M3 3v18h18M7 14l3-3 3 3 4-5',
        'target'   => 'M12 12m-9 0a9 9 0 1018 0 9 9 0 10-18 0M12 12m-5 0a5 5 0 1010 0 5 5 0 10-10 0M12 12m-1 0a1 1 0 102 0 1 1 0 10-2 0',
        'calendar' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z',
        'download' => 'M12 3v12m0 0l-4-4m4 4l4-4M5 21h14',
    ];
@endphp

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        title="Dokumen Publik"
        subtitle="Arsip regulasi, laporan kinerja, rencana strategis, dan berkas publik DPMPTSP Kota Surabaya yang dapat diakses dan diunduh secara terbuka." />

    <section class="container-page py-12">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $cat)
                <a href="{{ route($cat['route']) }}"
                   class="card group p-6 flex flex-col hover:shadow-md hover:-translate-y-0.5 transition will-change-transform">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary-50 text-primary-700 group-hover:bg-primary-700 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$cat['icon']] ?? $icons['download'] }}"/>
                        </svg>
                    </span>

                    <h2 class="mt-4 text-lg font-display font-bold text-primary-900">{{ $cat['label'] }}</h2>
                    <p class="mt-1.5 text-sm text-muted leading-relaxed flex-1">{{ $cat['desc'] }}</p>

                    <div class="mt-4 flex items-center justify-between">
                        @if (! is_null($cat['count']))
                            <span class="chip">{{ number_format($cat['count'], 0, ',', '.') }} {{ $cat['unit'] ?? 'item' }}</span>
                        @else
                            <span class="text-xs text-muted">Lihat dokumen</span>
                        @endif
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-primary-700">
                            Buka
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
