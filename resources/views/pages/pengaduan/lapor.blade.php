@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Pengaduan"
        title="Lapor Pengaduan"
        subtitle="Isi formulir berikut. Pengaduan akan ditangani sesuai SLA dan dapat dipantau via nomor tiket." />

    <section class="container-page py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data" class="lg:col-span-2 card-padded space-y-4">
                @csrf
                @honeypot

                @if (session('status'))
                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="rounded-lg bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-800">
                        <p class="font-semibold">Beberapa isian belum sesuai:</p>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium">Nama Lengkap <span class="text-rose-600">*</span></span>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required maxlength="120"
                               class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium">Email</span>
                        <input type="email" name="email" value="{{ old('email') }}" maxlength="255"
                               class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm font-medium">No. Telepon / WhatsApp</span>
                        <input type="text" name="phone" value="{{ old('phone') }}" maxlength="32"
                               class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                    </label>
                </div>

                <label class="block">
                    <span class="text-sm font-medium">Subjek <span class="text-rose-600">*</span></span>
                    <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="200"
                           class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Isi Pengaduan <span class="text-rose-600">*</span></span>
                    <textarea name="body" rows="6" required minlength="20" maxlength="5000"
                              class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none">{{ old('body') }}</textarea>
                    <span class="text-xs text-muted">Min. 20 karakter, maks. 5000 karakter.</span>
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Lampiran (opsional)</span>
                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           class="mt-1 w-full text-sm">
                    <span class="text-xs text-muted">Maks. 5 MB. Format: PDF, JPG, PNG, DOC, DOCX.</span>
                </label>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">Kirim Pengaduan</button>
                    <a href="{{ route('pengaduan.index') }}" class="btn-ghost">Batal</a>
                </div>
            </form>

            <aside class="space-y-4">
                <div class="card-padded">
                    <p class="heading-eyebrow">Yang Perlu Diketahui</p>
                    <ul class="mt-3 space-y-2 text-sm text-muted list-disc list-inside">
                        <li>Identitas pelapor dijamin kerahasiaannya.</li>
                        <li>Setiap pengaduan diberikan nomor tiket untuk tracking.</li>
                        <li>Tindak lanjut sesuai SLA pelayanan DPMPTSP.</li>
                        <li>Pengaduan tidak benar dapat ditolak / diarsipkan.</li>
                    </ul>
                </div>
                <div class="card-padded">
                    <p class="heading-eyebrow">Saluran Lain</p>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('pengaduan.sp4n') }}" class="hover:text-primary-700">SP4N LAPOR →</a></li>
                        <li><a href="{{ route('pengaduan.wbs') }}" class="hover:text-primary-700">Whistleblowing System →</a></li>
                        <li><a href="{{ route('pengaduan.tracking') }}" class="hover:text-primary-700">Tracking Pengaduan →</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </section>
@endsection
