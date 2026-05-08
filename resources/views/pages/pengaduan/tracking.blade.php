@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Pengaduan"
        title="Tracking Pengaduan"
        subtitle="Cek status pengaduan dengan nomor tiket yang Anda terima saat melapor." />

    <section class="container-page py-12">
        <div class="max-w-2xl">
            @if (session('status'))
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800 mb-4">{{ session('status') }}</div>
            @endif

            <form method="get" action="{{ route('pengaduan.tracking') }}" class="card-padded flex gap-2"
                  x-data="{ ticket: '{{ $ticket ?? '' }}' }"
                  @submit.prevent="if (ticket) window.location = '{{ url('/pengaduan/tracking') }}/' + encodeURIComponent(ticket.trim().toUpperCase())">
                <input type="text" x-model="ticket" placeholder="Mis. DP-20260508-A1B2C"
                       class="flex-1 px-3 py-2 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none font-mono"
                       required pattern="DP-[0-9]{8}-[A-Z0-9]{5}">
                <button type="submit" class="btn-primary">Cek Status</button>
            </form>

            @if (isset($ticket))
                @if ($complaint)
                    <div class="card-padded mt-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="heading-eyebrow">Tiket</p>
                                <p class="mt-1 font-mono text-lg font-semibold">{{ $complaint->ticket_no }}</p>
                            </div>
                            <span class="chip
                                @class([
                                    'bg-slate-100 text-slate-700' => $complaint->status === 'open',
                                    'bg-amber-50 !text-amber-700' => $complaint->status === 'in_progress',
                                    'bg-emerald-50 !text-emerald-700' => $complaint->status === 'resolved',
                                    'bg-rose-50 !text-rose-700' => $complaint->status === 'rejected',
                                ])">
                                @switch ($complaint->status)
                                    @case ('open') Diterima @break
                                    @case ('in_progress') Sedang Diproses @break
                                    @case ('resolved') Selesai @break
                                    @case ('rejected') Ditolak @break
                                    @default {{ $complaint->status }}
                                @endswitch
                            </span>
                        </div>

                        <dl class="mt-5 space-y-3 text-sm">
                            <div class="flex justify-between gap-3 border-b border-slate-100 pb-3">
                                <dt class="text-muted">Subjek</dt>
                                <dd class="font-medium text-right">{{ $complaint->subject }}</dd>
                            </div>
                            <div class="flex justify-between gap-3 border-b border-slate-100 pb-3">
                                <dt class="text-muted">Tanggal Lapor</dt>
                                <dd class="font-medium text-right">{{ $complaint->created_at->translatedFormat('d F Y · H:i') }}</dd>
                            </div>
                            <div class="flex justify-between gap-3 border-b border-slate-100 pb-3">
                                <dt class="text-muted">Saluran</dt>
                                <dd class="font-medium text-right uppercase">{{ $complaint->channel }}</dd>
                            </div>
                            @if ($complaint->responded_at)
                                <div class="flex justify-between gap-3 border-b border-slate-100 pb-3">
                                    <dt class="text-muted">Ditanggapi</dt>
                                    <dd class="font-medium text-right">{{ $complaint->responded_at->translatedFormat('d F Y · H:i') }}</dd>
                                </div>
                            @endif
                        </dl>

                        @if ($complaint->response)
                            <div class="mt-5">
                                <p class="heading-eyebrow">Tanggapan</p>
                                <p class="mt-2 text-sm text-ink whitespace-pre-line">{{ $complaint->response }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="card-padded mt-6 text-center">
                        <p class="text-muted">Tiket <span class="font-mono">{{ $ticket }}</span> tidak ditemukan.</p>
                        <a href="{{ route('pengaduan.lapor') }}" class="btn-ghost mt-3">Buat Pengaduan Baru</a>
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
