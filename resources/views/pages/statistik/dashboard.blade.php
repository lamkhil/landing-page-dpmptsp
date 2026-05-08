@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Statistik · DPMPTSP"
        :title="$pageTitle"
        subtitle="Data dikelola dan diperbarui berkala oleh DPMPTSP Kota Surabaya." />

    <section class="container-page py-12">
        @if (! empty($series))
            @php
                $palette = ['#0E4DA4', '#0891b2', '#059669', '#d97706', '#7c3aed'];
            @endphp

            <div class="space-y-10">
                @foreach ($series as $key => $s)
                    @php
                        $color = $palette[array_search($key, array_keys($series))] ?? '#0E4DA4';
                        $label = $groups[$key]['label'] ?? ucfirst($key);
                        $unit  = $groups[$key]['unit'] ?? null;
                        $latest = $s->last();
                        $first  = $s->first();
                        $delta = ($latest && $first && (float) $first->value > 0)
                            ? (($latest->value - $first->value) / $first->value) * 100
                            : null;
                    @endphp

                    <article class="grid lg:grid-cols-12 gap-5">
                        {{-- Chart --}}
                        <div class="lg:col-span-7">
                            <x-charts.line-trend
                                :title="$label"
                                :unit="$unit"
                                :series="$s"
                                :color="$color" />
                        </div>

                        {{-- Detail data table + headline KPI --}}
                        <div class="lg:col-span-5 space-y-4">
                            <div class="card-padded">
                                <p class="text-[11px] font-bold tracking-widest uppercase text-primary-700">{{ $label }}</p>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <p class="text-3xl md:text-4xl font-display font-bold text-ink">
                                        {{ $latest ? number_format((float) $latest->value, 0, ',', '.') : '—' }}
                                    </p>
                                    @if ($unit)
                                        <p class="text-sm text-muted">{{ $unit }}</p>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-muted">Tahun {{ $latest?->year ?? '—' }}</p>
                                @if ($delta !== null)
                                    @php $up = $delta >= 0; @endphp
                                    <p class="mt-3 text-xs">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $up ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} font-semibold">
                                            {{ $up ? '↑' : '↓' }} {{ number_format(abs($delta), 1, ',', '.') }}%
                                        </span>
                                        <span class="text-muted ml-1">{{ $up ? 'naik' : 'turun' }} dari {{ $first?->year }}</span>
                                    </p>
                                @endif
                            </div>

                            <div class="card overflow-hidden">
                                <div class="px-5 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                    <h3 class="text-xs font-semibold text-ink">Data Tabel</h3>
                                    <span class="text-[11px] text-muted">{{ $s->count() }} titik data</span>
                                </div>
                                <table class="w-full text-sm">
                                    <thead class="bg-white border-b border-slate-100 text-muted">
                                        <tr>
                                            <th class="text-left px-5 py-2 text-xs font-semibold">Tahun</th>
                                            <th class="text-right px-5 py-2 text-xs font-semibold">Nilai</th>
                                            <th class="text-left px-5 py-2 text-xs font-semibold hidden md:table-cell">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($s as $row)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-5 py-2 font-mono text-xs">{{ $row->year }}</td>
                                                <td class="px-5 py-2 text-right tabular-nums font-medium">{{ number_format((float) $row->value, 0, ',', '.') }}</td>
                                                <td class="px-5 py-2 text-xs text-muted hidden md:table-cell">{{ $row->label ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </article>

                    @if (! $loop->last)
                        <hr class="border-slate-100">
                    @endif
                @endforeach
            </div>
        @else
            <div class="card-padded text-center text-muted">Belum ada data statistik untuk halaman ini.</div>
        @endif

        <div class="mt-12 flex items-center justify-between flex-wrap gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('statistik.index') }}" class="btn-ghost">← Kembali ke Dashboard</a>
            <p class="text-xs text-muted">Sumber: DPMPTSP Kota Surabaya</p>
        </div>
    </section>
@endsection
