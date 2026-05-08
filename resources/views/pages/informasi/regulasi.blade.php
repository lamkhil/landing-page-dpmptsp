@extends('layouts.public')

@section('title', $pageTitle)

@section('content')
    <x-page-header
        eyebrow="Informasi Publik"
        title="Regulasi"
        subtitle="Peraturan Daerah, Peraturan Walikota, dan Surat Keputusan terkait pelayanan DPMPTSP." />

    <section class="container-page py-10">
        <form method="get" class="card-padded flex items-center gap-2 flex-wrap">
            <select name="tahun" class="px-3 py-2 rounded-lg border border-slate-200">
                <option value="">Semua tahun</option>
                @foreach ($years as $y)
                    <option value="{{ $y }}" @selected($activeYear == $y)>{{ $y }}</option>
                @endforeach
            </select>
            <select name="jenis" class="px-3 py-2 rounded-lg border border-slate-200">
                <option value="">Semua jenis</option>
                @foreach ($types as $t)
                    <option value="{{ $t }}" @selected($activeType == $t)>{{ strtoupper($t) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Filter</button>
            @if ($activeYear || $activeType)
                <a href="{{ url()->current() }}" class="btn-ghost">Reset</a>
            @endif
        </form>
    </section>

    <section class="container-page pb-20">
        @if ($paginator->isEmpty())
            <div class="card-padded text-center text-muted">Tidak ada regulasi sesuai filter.</div>
        @else
            <div class="card overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No. Dokumen</th>
                        <th class="text-left px-4 py-3 font-semibold">Judul</th>
                        <th class="text-left px-4 py-3 font-semibold hidden md:table-cell">Jenis</th>
                        <th class="text-left px-4 py-3 font-semibold hidden md:table-cell">Tahun</th>
                        <th class="text-right px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @foreach ($paginator as $r)
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs whitespace-nowrap">{{ $r->doc_number }}</td>
                            <td class="px-4 py-3">{{ $r->title }}</td>
                            <td class="px-4 py-3 hidden md:table-cell"><span class="chip">{{ strtoupper($r->doc_type) }}</span></td>
                            <td class="px-4 py-3 hidden md:table-cell">{{ $r->doc_year }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ asset('storage/'.$r->file_path) }}" target="_blank" rel="noopener" class="btn-outline text-xs">Unduh</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-10">{{ $paginator->withQueryString()->links() }}</div>
        @endif
    </section>
@endsection
