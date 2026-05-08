@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="DPMPTSP Surabaya"
        title="Kontak Kami"
        subtitle="Sampaikan pertanyaan, saran, atau permintaan informasi via formulir di bawah ini." />

    <section class="container-page py-12 grid gap-8 lg:grid-cols-3">
        {{-- Form --}}
        <form method="POST" action="{{ route('kontak.store') }}" class="lg:col-span-2 card-padded space-y-4">
            @csrf
            @honeypot

            @if (session('status'))
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-800">
                    <p class="font-semibold">Mohon perbaiki:</p>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium">Nama <span class="text-rose-600">*</span></span>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                           class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                </label>
                <label class="block">
                    <span class="text-sm font-medium">Email <span class="text-rose-600">*</span></span>
                    <input type="email" name="email" value="{{ old('email') }}" required maxlength="255"
                           class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                </label>
            </div>
            <label class="block">
                <span class="text-sm font-medium">Subjek <span class="text-rose-600">*</span></span>
                <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="200"
                       class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
            </label>
            <label class="block">
                <span class="text-sm font-medium">Pesan <span class="text-rose-600">*</span></span>
                <textarea name="body" rows="5" required minlength="10" maxlength="3000"
                          class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">{{ old('body') }}</textarea>
            </label>
            <button type="submit" class="btn-primary">Kirim Pesan</button>
        </form>

        {{-- Sidebar contact info --}}
        <aside class="space-y-4">
            <div class="card-padded">
                <p class="heading-eyebrow">Alamat</p>
                <address class="mt-2 not-italic text-sm">{{ $settings->address ?? 'Jl. Tunjungan No. 1-3, Surabaya 60275' }}</address>
            </div>
            @if ($settings->phone ?? null)
                <div class="card-padded">
                    <p class="heading-eyebrow">Telepon</p>
                    <p class="mt-1"><a href="tel:{{ preg_replace('/\D/', '', $settings->phone) }}" class="font-medium hover:text-primary-700">{{ $settings->phone }}</a></p>
                </div>
            @endif
            @if ($settings->email ?? null)
                <div class="card-padded">
                    <p class="heading-eyebrow">Email</p>
                    <p class="mt-1"><a href="mailto:{{ $settings->email }}" class="font-medium hover:text-primary-700">{{ $settings->email }}</a></p>
                </div>
            @endif
            @if ($settings->office_hours ?? null)
                <div class="card-padded">
                    <p class="heading-eyebrow">Jam Pelayanan</p>
                    <p class="mt-1 text-sm">{{ $settings->office_hours }}</p>
                </div>
            @endif
        </aside>
    </section>

    @if ($settings->embed_map_url ?? null)
        <section class="container-page pb-20">
            <p class="heading-eyebrow">Lokasi</p>
            <h2 class="mt-1 text-2xl font-bold text-primary-900">Kantor DPMPTSP Surabaya</h2>
            <div class="mt-4 aspect-[16/9] rounded-2xl overflow-hidden border border-slate-100">
                <iframe src="{{ $settings->embed_map_url }}" class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
        </section>
    @endif
@endsection
