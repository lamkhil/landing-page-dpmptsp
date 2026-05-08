@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Kontak"
        title="Lokasi Kantor"
        subtitle="Kunjungi kantor DPMPTSP Kota Surabaya pada jam pelayanan." />

    <section class="container-page py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @if ($settings->embed_map_url ?? null)
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden border border-slate-100">
                        <iframe src="{{ $settings->embed_map_url }}" class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                @else
                    <div class="aspect-[16/10] rounded-2xl bg-slate-100 grid place-items-center text-muted">Peta belum diatur.</div>
                @endif
            </div>
            <aside class="space-y-4">
                <div class="card-padded">
                    <p class="heading-eyebrow">Alamat</p>
                    <address class="mt-2 not-italic text-sm">{{ $settings->address }}</address>
                </div>
                @if ($settings->phone ?? null)
                    <div class="card-padded"><p class="heading-eyebrow">Telepon</p><p class="mt-1">{{ $settings->phone }}</p></div>
                @endif
                @if ($settings->office_hours ?? null)
                    <div class="card-padded"><p class="heading-eyebrow">Jam Pelayanan</p><p class="mt-1 text-sm">{{ $settings->office_hours }}</p></div>
                @endif
            </aside>
        </div>
    </section>
@endsection
