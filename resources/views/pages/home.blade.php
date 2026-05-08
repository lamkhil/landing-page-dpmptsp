@extends('layouts.public')

@section('title', $slides->first()?->title ?? 'Beranda')
@section('meta_description', $seo?->meta_description ?? 'Portal resmi DPMPTSP Kota Surabaya — pelayanan publik modern,
    transparan, dan akuntabel.')

@section('content')

    {{-- Running text marquee — pengumuman + berita + agenda terbaru, scroll horizontal terus-menerus --}}
    <x-running-text :items="$tickerItems" badge="Info Terkini" :speed="60" />
    {{-- Wave divider into the next section --}}
    {{-- ════════════════════════════════════════════════════════════════
     HERO CAROUSEL  +  decorations: dot grid + grid lines + blobs
     ════════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-primary-950 text-white overflow-hidden" x-data="{
        current: 0,
        total: {{ max(1, $slides->count()) }},
        timer: null,
        start() { if (this.total > 1) this.timer = setInterval(() => this.next(), 6500); },
        stop() { clearInterval(this.timer);
            this.timer = null; },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
        go(i) { this.current = i;
            this.stop();
            this.start(); },
    }" x-init="start()"
        @mouseenter="stop()" @mouseleave="start()" aria-label="Hero carousel">

        {{-- Photo backgrounds, one per slide, with dark overlay for text contrast --}}
        @php $heroPhotos = ['/photos/hero-1.png', '/photos/hero-2.jpg', '/photos/hero-3.jpg']; @endphp
        @foreach ($slides as $i => $_slide)
            <div x-show="current === {{ $i }}" x-transition.opacity.duration.700ms
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ $heroPhotos[$i % count($heroPhotos)] }}');" aria-hidden="true"></div>
        @endforeach
        {{-- Dark overlay so heading remains legible over any photo --}}
        <div class="absolute inset-0 bg-gradient-to-br from-primary-950/95 via-primary-900/85 to-primary-800/75"></div>

        {{-- Decorations --}}
        <x-decor.grid-lines class="opacity-50" color="rgb(255 255 255 / 0.04)" />
        <x-decor.dots class="top-8 right-8 w-64 h-64 opacity-60" color="rgb(34 211 238 / 0.25)" />
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-accent-500/10 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full bg-primary-500/15 blur-3xl"></div>
        {{-- Surabaya batik ornament — bottom-right --}}
        <img src="/brand/icon_batik_hijau_kanan.svg" alt="" aria-hidden="true"
            class="absolute -bottom-2 right-0 w-40 md:w-56 lg:w-72 opacity-30 pointer-events-none mix-blend-screen" />

        {{-- Slides --}}
        <div class="relative">
            @forelse ($slides as $i => $slide)
                <div x-show="current === {{ $i }}" x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-300 absolute inset-0"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="container-page py-20 lg:py-28">
                    <div class="max-w-3xl">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent-500/15 border border-accent-400/30 backdrop-blur">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                            <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400">
                                {{ $slide->subtitle ?? 'DPMPTSP Surabaya' }}
                            </span>
                        </span>
                        <h1
                            class="mt-5 font-display font-extrabold text-white text-4xl md:text-5xl lg:text-6xl leading-[1.1] tracking-tight">
                            {{ $slide->title }}
                        </h1>
                        @if ($slide->description)
                            <p class="mt-6 text-lg text-slate-200 leading-relaxed max-w-2xl">{{ $slide->description }}</p>
                        @endif
                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            @if ($slide->cta_label)
                                <a href="{{ $slide->cta_url ?? '#' }}"
                                    class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-white text-primary-900 hover:bg-slate-100 transition shadow-lg shadow-primary-950/30">
                                    {{ $slide->cta_label }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                            @if ($slide->secondary_cta_label)
                                <a href="{{ $slide->secondary_cta_url ?? '#' }}"
                                    class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold border border-white/30 text-white hover:bg-white/10 transition">
                                    {{ $slide->secondary_cta_label }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="container-page py-20">
                    <h1 class="font-display font-extrabold text-white text-4xl">DPMPTSP Surabaya</h1>
                </div>
            @endforelse
        </div>

        {{-- Pagination dots + arrows --}}
        @if ($slides->count() > 1)
            <div class="relative pb-10 flex justify-center items-center gap-3">
                <button type="button" @click="prev(); stop(); start()"
                    class="p-2 rounded-full hover:bg-white/10 transition" aria-label="Slide sebelumnya">
                    <svg class="w-5 h-5 text-white/70 hover:text-white" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    @foreach ($slides as $i => $_)
                        <button type="button" @click="go({{ $i }})"
                            :class="current === {{ $i }} ? 'w-8 bg-accent-400' : 'w-2 bg-white/30 hover:bg-white/50'"
                            class="h-2 rounded-full transition-all" aria-label="Slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>
                <button type="button" @click="next(); stop(); start()"
                    class="p-2 rounded-full hover:bg-white/10 transition" aria-label="Slide berikutnya">
                    <svg class="w-5 h-5 text-white/70 hover:text-white" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="relative">
            <x-decor.wave fill="#f8fafc" />
        </div>

    </section>


    {{-- ════════════════════════════════════════════════════════════════
     STATISTIK STRIP — counter cards (sit on white, shadow lifted)
     ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-surface relative mt-10">
        <x-decor.dots class="top-0 right-0 w-72 h-40 opacity-70" color="rgb(14 77 164 / 0.08)" />
        <div class="container-page py-10 -mt-12 relative z-10">
            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5 bg-white rounded-2xl shadow-xl shadow-primary-950/10 border border-slate-100 p-6">
                @forelse ($counters->take(4) as $c)
                    <div x-data="counter({{ (int) round($c->value) }})" x-intersect="start" class="text-center md:text-left">
                        <p class="text-[10px] md:text-xs font-bold tracking-wider uppercase text-primary-700">
                            {{ $c->unit ?? '' }}</p>
                        <p class="mt-1 text-2xl md:text-4xl font-display font-bold text-ink leading-none"
                            x-text="value.toLocaleString('id-ID')">0</p>
                        <p class="mt-1 text-[11px] md:text-sm text-muted">{{ $c->label }}</p>
                    </div>
                @empty
                    <p class="col-span-full text-sm text-muted text-center">Statistik belum tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>


    {{-- ════════════════════════════════════════════════════════════════
     #01  APLIKASI PUBLIK
     ════════════════════════════════════════════════════════════════ --}}
    @if ($applications->isNotEmpty())
        <section class="container-page py-16 lg:py-20 relative">
            <div class="relative flex items-end justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Aplikasi Publik</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Akses cepat ke <span
                            class="italic text-primary-700">layanan digital</span></h2>
                    <p class="mt-2 text-sm text-muted max-w-xl">Daftar aplikasi resmi DPMPTSP Surabaya — perizinan,
                        investasi, pengaduan, hingga open data.</p>
                </div>
                <a href="{{ route('aplikasi.index') }}"
                    class="text-sm font-semibold text-primary-700 hover:text-primary-800">Semua aplikasi →</a>
            </div>
            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4 relative">
                @foreach ($applications as $app)
                    <a href="{{ $app->url }}" @if ($app->link_type === 'external') target="_blank" rel="noopener" @endif
                        class="group block bg-white rounded-xl border border-slate-100 p-5 hover:border-primary-200 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <div
                            class="w-11 h-11 rounded-xl bg-primary-50 grid place-items-center text-primary-700 group-hover:bg-primary-700 group-hover:text-white transition">
                            @if ($app->icon_path)
                                <img src="{{ asset('storage/' . $app->icon_path) }}" alt=""
                                    class="w-6 h-6 object-contain">
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            @endif
                        </div>
                        <p class="mt-3 font-semibold text-ink leading-snug">{{ $app->name }}</p>
                        <p class="mt-1 text-xs text-muted">{{ $app->category_name ?? 'Aplikasi' }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif



    {{-- ════════════════════════════════════════════════════════════════
     #02  HIGHLIGHT LAYANAN
     ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden">
        <x-decor.dots class="-bottom-10 -left-10 w-80 h-80 opacity-50" color="rgb(14 77 164 / 0.10)" />
        <div class="container-page py-16 lg:py-20 relative">
            <div class="flex items-end justify-between flex-wrap gap-3 relative">
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Layanan</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Pelayanan utama bagi <span
                            class="italic text-primary-700">masyarakat</span></h2>
                </div>
                <a href="{{ route('layanan.index') }}"
                    class="text-sm font-semibold text-primary-700 hover:text-primary-800">Semua layanan →</a>
            </div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 relative">
                @foreach ([['Perizinan Berusaha', 'Pengajuan & penerbitan izin via OSS RBA.', 'layanan.perizinan', 'M9 12h6m-6 4h6m-7 4h8a2 2 0 002-2V8l-4-4H7a2 2 0 00-2 2v12a2 2 0 002 2z'], ['Konsultasi Online', 'Klinik Investasi DPMPTSP Surabaya.', 'layanan.konsultasi', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'], ['Tracking Izin', 'Pantau status pengajuan secara real-time.', 'layanan.tracking', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'], ['SLA Pelayanan', 'Standar waktu setiap jenis layanan.', 'layanan.sla', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']] as [$title, $desc, $route, $iconPath])
                    <a href="{{ route($route) }}"
                        class="group block bg-white rounded-xl border border-slate-100 p-5 hover:border-primary-200 hover:shadow-lg transition">
                        <div class="w-10 h-10 rounded-lg bg-primary-700 text-white grid place-items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-semibold text-ink group-hover:text-primary-700 transition">
                            {{ $title }}</h3>
                        <p class="mt-1 text-sm text-muted leading-relaxed">{{ $desc }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ════════════════════════════════════════════════════════════════
     NILAI PELAYANAN — full-width DARK
     ════════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white overflow-hidden">
        {{-- Surabaya panorama photo as faint backdrop --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-15"
            style="background-image: url('/photos/PembangunanSurabaya.webp');" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary-950/95 via-primary-900/90 to-primary-800/85"
            aria-hidden="true"></div>

        <x-decor.grid-lines class="opacity-50" color="rgb(255 255 255 / 0.04)" />
        <x-decor.dots class="top-10 right-10 w-72 h-72 opacity-50" color="rgb(34 211 238 / 0.20)" />
        <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full bg-accent-500/10 blur-3xl"></div>
        <div class="absolute -top-20 right-1/4 w-[300px] h-[300px] rounded-full bg-primary-500/10 blur-3xl"></div>
        {{-- Batik ornament Surabaya khas --}}
        <img src="/brand/icon_batik_coklat_kiri.svg" alt="" aria-hidden="true"
            class="absolute -bottom-2 left-0 w-32 md:w-44 lg:w-56 opacity-25 pointer-events-none mix-blend-screen" />

        <div class="container-page py-16 lg:py-24 relative">
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest uppercase text-accent-400">Nilai Pelayanan</p>
                <h2 class="mt-2 text-2xl md:text-4xl font-display font-bold leading-tight">5 prinsip pelayanan publik <em
                        class="not-italic text-accent-400">DPMPTSP Surabaya</em></h2>
                <p class="mt-4 text-slate-200 leading-relaxed">Komitmen yang kami pegang dalam setiap proses perizinan —
                    sesuai semangat Reformasi Birokrasi & Zona Integritas WBK/WBBM.</p>
            </div>

            <div class="mt-10 grid gap-4 md:grid-cols-3 lg:grid-cols-5">
                @foreach ([['title' => 'Cepat', 'desc' => 'Sesuai SLA pelayanan', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'], ['title' => 'Mudah', 'desc' => 'Online 24 jam, satu pintu', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'], ['title' => 'Akuntabel', 'desc' => 'Audit trail seluruh proses', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'], ['title' => 'Transparan', 'desc' => 'Status real-time, tracking online', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'], ['title' => 'Bebas Pungli', 'desc' => 'Komitmen WBK & WBBM', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z']] as $nilai)
                    <div
                        class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-5 hover:bg-white/10 transition">
                        <div class="w-11 h-11 rounded-xl bg-accent-500/20 grid place-items-center text-accent-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $nilai['icon'] }}" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-display font-bold text-lg leading-snug">{{ $nilai['title'] }}</h3>
                        <p class="mt-1 text-xs text-slate-300 leading-relaxed">{{ $nilai['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     #03  PROFIL + MENGAPA SURABAYA
     ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-16 lg:py-20 relative">
        <div class="grid lg:grid-cols-12 gap-10 items-start relative">
            <div class="lg:col-span-7">
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Tentang Kami</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">
                    {{ $profilSnippet?->title ?? 'Profil DPMPTSP Kota Surabaya' }}</h2>
                <div
                    class="mt-4 prose prose-slate max-w-none prose-p:text-muted prose-p:leading-relaxed prose-a:text-primary-700 prose-strong:text-ink line-clamp-[12]">
                    @if ($profilSnippet)
                        {!! $profilSnippet->excerpt
                            ? '<p>' . e($profilSnippet->excerpt) . '</p>'
                            : \Illuminate\Support\Str::limit(strip_tags($profilSnippet->body), 600) !!}
                    @else
                        <p>Lembaga yang menyelenggarakan pelayanan perizinan terpadu satu pintu serta pengembangan iklim
                            investasi di Kota Surabaya.</p>
                    @endif
                </div>
                <div class="mt-5 flex flex-wrap gap-2 text-sm">
                    <a href="{{ route('profil.index') }}"
                        class="font-semibold text-primary-700 hover:text-primary-800">Profil lengkap →</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('profil.visi-misi') }}"
                        class="font-semibold text-primary-700 hover:text-primary-800">Visi & Misi →</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('profil.struktur') }}"
                        class="font-semibold text-primary-700 hover:text-primary-800">Struktur Organisasi →</a>
                </div>
            </div>

            <div class="lg:col-span-5 relative">
                <div
                    class="relative bg-gradient-to-br from-primary-700 to-primary-900 rounded-2xl p-7 text-white shadow-xl shadow-primary-950/20 overflow-hidden">
                    <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.04)" />
                    <x-decor.dots class="-top-4 -right-4 w-32 h-32" color="rgb(34 211 238 / 0.30)" />
                    <div class="relative">
                        <p class="text-xs font-bold tracking-widest uppercase text-accent-400">Mengapa Surabaya?</p>
                        <h3 class="mt-1 text-xl font-display font-bold leading-snug">6 alasan investasi di <em
                                class="not-italic text-accent-400">Kota Pahlawan</em></h3>
                        <ul class="mt-4 space-y-2 text-sm text-slate-200">
                            @foreach (['Kota Metropolitan ke-2 Indonesia', 'Bandara & Pelabuhan Internasional', 'Fasilitas Kesehatan Bertaraf Internasional', 'Universitas Berstandar Internasional', '"Kota 1000 Taman" — fasilitas umum terjaga', 'Iklim investasi tumbuh sangat cepat'] as $alasan)
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-accent-400 shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $alasan }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('profil.mengapa') }}"
                            class="inline-flex items-center gap-1 mt-5 text-sm font-semibold text-accent-400 hover:text-accent-500">
                            Selengkapnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     #04  STATISTIK INTERAKTIF (tab switcher + chart + detail link)
     ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden">
        <x-decor.dots class="top-10 right-10 w-60 h-60 opacity-60" color="rgb(14 77 164 / 0.10)" />
        <div class="container-page py-16 lg:py-20 relative">
            <div class="flex items-end justify-between flex-wrap gap-3 relative">
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Statistik</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Tren <span
                            class="italic text-primary-700">5 tahun terakhir</span></h2>
                    <p class="mt-2 text-sm text-muted max-w-xl">Pilih indikator di bawah untuk melihat tren. Klik <em>Lihat
                            detail</em> untuk dashboard lengkap.</p>
                </div>
                <a href="{{ route('statistik.index') }}"
                    class="text-sm font-semibold text-primary-700 hover:text-primary-800">Dashboard lengkap →</a>
            </div>

            <div class="mt-8 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm relative" x-data="statsTabs({{ \Illuminate\Support\Js::from($trendStats) }}, 'izin')">

                {{-- Tab buttons --}}
                <div class="flex gap-2 flex-wrap">
                    <template x-for="(stat, key) in stats" :key="key">
                        <button type="button" @click="active = key"
                            :class="active === key ? 'bg-primary-700 text-white border-primary-700 shadow-sm' :
                                'bg-white text-ink border-slate-200 hover:border-primary-300 hover:text-primary-700'"
                            class="rounded-full px-4 py-2 text-xs md:text-sm font-semibold transition border">
                            <span x-text="stat.label"></span>
                        </button>
                    </template>
                </div>

                {{-- Stat headline (latest value of active tab) --}}
                <div class="mt-6 flex flex-wrap items-baseline gap-x-4 gap-y-1">
                    <span class="text-[11px] font-bold tracking-widest uppercase text-primary-700"
                        x-text="stats[active].label"></span>
                    <span class="text-3xl md:text-4xl font-display font-bold text-ink"
                        x-text="stats[active].latest ? Number(stats[active].latest).toLocaleString('id-ID') : '—'"></span>
                    <span class="text-sm text-muted" x-text="stats[active].unit"></span>
                    <span class="text-xs text-muted ml-auto">Tahun <span
                            x-text="stats[active].latest_year || '—'"></span></span>
                </div>

                {{-- Chart canvas --}}
                <div x-ref="chart" class="mt-4 min-h-[320px]"></div>

                {{-- Footer with detail link --}}
                <div class="mt-2 pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                    <p class="text-xs text-muted">Sumber: DPMPTSP Kota Surabaya — <span
                            x-text="(stats[active].categories?.length || 0) + ' titik data'"></span>.</p>
                    <a :href="stats[active].detail_url"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-primary-700 hover:text-primary-800">
                        Lihat detail data
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     KOMITMEN LAYANAN DIGITAL — full-width ACCENT (cyan→primary)
     ════════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-r from-accent-600 via-primary-700 to-primary-900 text-white overflow-hidden">
        {{-- Photo backdrop: kegiatan pelatihan UMKM --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-20"
            style="background-image: url('/photos/PelatihanUMKM.webp');" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-accent-600/85 via-primary-700/85 to-primary-900/90"
            aria-hidden="true"></div>

        <x-decor.dots class="-top-8 -left-8 w-72 h-72 opacity-50" color="rgb(255 255 255 / 0.15)" />
        <x-decor.dots class="-bottom-8 right-1/4 w-60 h-60 opacity-40" color="rgb(255 255 255 / 0.12)" />
        <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full bg-white/5 blur-3xl"></div>
        {{-- Batik ornament right-bottom --}}
        <img src="/brand/icon_batik_hijau.svg" alt="" aria-hidden="true"
            class="absolute -bottom-3 -right-3 w-32 md:w-40 opacity-25 pointer-events-none mix-blend-screen" />

        <div class="container-page py-14 lg:py-16 relative">
            <div class="grid md:grid-cols-12 gap-8 items-center">
                <div class="md:col-span-7">
                    <p class="text-xs font-bold tracking-widest uppercase text-white/80">Komitmen Digital</p>
                    <h2 class="mt-2 text-2xl md:text-3xl lg:text-4xl font-display font-bold leading-tight">Layanan publik
                        yang <em class="not-italic">ada saat Anda butuhkan</em></h2>
                    <p class="mt-3 text-white/90 leading-relaxed max-w-xl">Akses 10+ aplikasi resmi DPMPTSP, OSS RBA, dan
                        SSW Alfa Surabaya — semua online, 24 jam, dengan pelayanan satu pintu.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('aplikasi.index') }}"
                            class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold bg-white text-primary-900 hover:bg-slate-100 transition shadow-lg">
                            Jelajah Aplikasi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('layanan.perizinan') }}"
                            class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold border border-white/40 text-white hover:bg-white/10 transition">
                            Mulai Ajukan Izin
                        </a>
                    </div>
                </div>

                <div class="md:col-span-5">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center bg-white/10 backdrop-blur border border-white/20 rounded-xl p-4">
                            <p class="text-2xl md:text-3xl font-display font-bold text-white">10+</p>
                            <p class="mt-1 text-[11px] text-white/80 leading-tight">Aplikasi Resmi</p>
                        </div>
                        <div class="text-center bg-white/10 backdrop-blur border border-white/20 rounded-xl p-4">
                            <p class="text-2xl md:text-3xl font-display font-bold text-white">1</p>
                            <p class="mt-1 text-[11px] text-white/80 leading-tight">Pintu Pelayanan</p>
                        </div>
                        <div class="text-center bg-white/10 backdrop-blur border border-white/20 rounded-xl p-4">
                            <p class="text-2xl md:text-3xl font-display font-bold text-white">24/7</p>
                            <p class="mt-1 text-[11px] text-white/80 leading-tight">Layanan Online</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     #05  BERITA + PENGUMUMAN + AGENDA
     ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-16 lg:py-20 relative">
        <div class="flex items-end justify-between flex-wrap gap-3 relative">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Informasi Publik</p>
                <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Update <span
                        class="italic text-primary-700">terbaru</span> DPMPTSP</h2>
            </div>
            <a href="{{ route('informasi.index') }}"
                class="text-sm font-semibold text-primary-700 hover:text-primary-800">Semua informasi →</a>
        </div>

        <div class="mt-6 grid lg:grid-cols-3 gap-5 relative">
            <div class="lg:col-span-2">
                <h3 class="text-sm font-semibold text-ink mb-3">📰 Berita Terbaru</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    @forelse ($latestNews as $n)
                        <article
                            class="bg-white rounded-xl border border-slate-100 overflow-hidden hover:shadow-md transition">
                            @if ($n->cover_path)
                                <img src="{{ asset('storage/' . $n->cover_path) }}" alt=""
                                    class="w-full h-36 object-cover" loading="lazy">
                            @else
                                <div
                                    class="w-full h-36 bg-gradient-to-br from-primary-100 to-primary-50 grid place-items-center">
                                    <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM9 9h1m0 4h6m-6 4h6" />
                                    </svg>
                                </div>
                            @endif
                            <div class="p-4">
                                <p class="text-[11px] text-muted">{{ $n->published_at?->translatedFormat('d F Y') }}</p>
                                <h4 class="mt-1 font-semibold leading-snug line-clamp-2">
                                    <a href="{{ route('informasi.berita.show', $n->slug) }}"
                                        class="text-ink hover:text-primary-700">{{ $n->title }}</a>
                                </h4>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-muted">Belum ada berita.</p>
                    @endforelse
                </div>
            </div>

            <aside class="space-y-4">
                <div class="bg-white rounded-xl border border-slate-100 p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-ink">📢 Pengumuman</h3>
                        <a href="{{ route('informasi.pengumuman.index') }}"
                            class="text-[11px] text-primary-700 hover:underline">Semua →</a>
                    </div>
                    <ul class="mt-3 space-y-2.5 text-sm">
                        @forelse ($latestAnnounce as $p)
                            <li class="border-b border-slate-100 pb-2.5 last:border-0 last:pb-0">
                                <a href="{{ route('informasi.pengumuman.show', $p->slug) }}"
                                    class="font-medium text-ink hover:text-primary-700 leading-snug block line-clamp-2">{{ $p->title }}</a>
                                <p class="text-[11px] text-muted mt-0.5">
                                    {{ $p->published_at?->translatedFormat('d M Y') }}</p>
                            </li>
                        @empty
                            <li class="text-muted text-xs">Belum ada pengumuman.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white rounded-xl border border-slate-100 p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-ink">📅 Agenda</h3>
                        <a href="{{ route('informasi.agenda.index') }}"
                            class="text-[11px] text-primary-700 hover:underline">Semua →</a>
                    </div>
                    <ul class="mt-3 space-y-2.5 text-sm">
                        @forelse ($upcomingAgendas as $a)
                            <li class="flex gap-3 border-b border-slate-100 pb-2.5 last:border-0 last:pb-0">
                                <div class="text-center shrink-0 w-12 rounded-lg bg-primary-50 p-1.5">
                                    <p class="text-[10px] uppercase text-primary-700 font-bold leading-none">
                                        {{ $a->starts_at->translatedFormat('M') }}</p>
                                    <p class="text-lg font-bold text-ink leading-none mt-1">
                                        {{ $a->starts_at->format('d') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-ink leading-snug line-clamp-2">{{ $a->title }}</p>
                                    @if ($a->location)
                                        <p class="text-[11px] text-muted truncate">📍 {{ $a->location }}</p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="text-muted text-xs">Belum ada agenda.</li>
                        @endforelse
                    </ul>
                </div>
            </aside>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     #06  REGULASI + FAQ
     ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 border-y border-slate-100 relative overflow-hidden">
        <x-decor.dots class="bottom-0 left-0 w-72 h-60 opacity-50" color="rgb(14 77 164 / 0.08)" />
        <div class="container-page py-16 lg:py-20 relative">
            <div class="grid lg:grid-cols-2 gap-8 relative">
                {{-- Regulasi --}}
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Regulasi</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Regulasi <span
                            class="italic text-primary-700">Terbaru</span></h2>
                    <div class="mt-5 bg-white rounded-2xl border border-slate-100 divide-y divide-slate-100">
                        @forelse ($latestRegs as $r)
                            <div class="p-4 hover:bg-slate-50 transition">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-xs font-mono text-muted">{{ strtoupper($r->doc_type) }} ·
                                            {{ $r->doc_number }} / {{ $r->doc_year }}</p>
                                        <p class="font-medium text-ink leading-snug mt-0.5 line-clamp-2">
                                            {{ $r->title }}</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank" rel="noopener"
                                        class="shrink-0 inline-flex items-center gap-1 text-xs font-semibold text-primary-700 hover:text-primary-800">
                                        Unduh
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-sm text-muted">Belum ada regulasi terdaftar.</div>
                        @endforelse
                    </div>
                    <a href="{{ route('informasi.regulasi.index') }}"
                        class="inline-block mt-4 text-sm font-semibold text-primary-700 hover:text-primary-800">Semua
                        regulasi →</a>
                </div>

                {{-- FAQ --}}
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">FAQ</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Pertanyaan yang <span
                            class="italic text-primary-700">sering diajukan</span></h2>
                    <div class="mt-5 space-y-3">
                        @foreach ($topFaqs as $faq)
                            <details class="bg-white rounded-xl border border-slate-100 px-5 py-4 group">
                                <summary
                                    class="cursor-pointer flex items-center justify-between gap-3 list-none font-semibold text-ink">
                                    <span>{{ $faq->question }}</span>
                                    <svg class="w-5 h-5 text-muted transition group-open:rotate-180 shrink-0"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="mt-3 text-sm text-muted prose prose-sm max-w-none">{!! $faq->body !!}
                                </div>
                            </details>
                        @endforeach
                    </div>
                    <a href="{{ route('profil.faq') }}"
                        class="inline-block mt-4 text-sm font-semibold text-primary-700 hover:text-primary-800">Semua FAQ
                        →</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
     #07  ZONA INTEGRITAS + PENGADUAN CTA
     ════════════════════════════════════════════════════════════════ --}}
    <section class="container-page py-16 lg:py-20 relative">
        <div class="grid lg:grid-cols-2 gap-6 relative">
            <div
                class="relative bg-gradient-to-br from-primary-50 to-white border border-primary-100 rounded-2xl p-8 lg:p-10 overflow-hidden">
                <x-decor.dots class="top-0 right-0 w-40 h-40 opacity-70" color="rgb(14 77 164 / 0.10)" />
                <div class="relative">
                    <p class="text-xs font-bold tracking-widest uppercase text-primary-700">Zona Integritas</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold text-ink">Komitmen <em
                            class="not-italic text-primary-700">WBK & WBBM</em></h2>
                    <p class="mt-3 text-muted leading-relaxed">Membangun pelayanan publik yang bersih dari korupsi, kolusi,
                        dan nepotisme — sesuai prinsip Reformasi Birokrasi.</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-primary-700 text-white text-xs font-medium px-3 py-1">Wilayah
                            Bebas Korupsi</span>
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-primary-50 text-primary-700 border border-primary-200 text-xs font-medium px-3 py-1">Menuju WBBM</span>
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-primary-50 text-primary-700 text-xs font-medium px-3 py-1">Reformasi
                            Birokrasi</span>
                    </div>
                    <a href="{{ route('profil.zi') }}"
                        class="inline-block mt-6 text-sm font-semibold text-primary-700 hover:text-primary-800">Selengkapnya
                        →</a>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-primary-900 to-primary-700 rounded-2xl p-8 lg:p-10 text-white overflow-hidden">
                <x-decor.grid-lines class="opacity-100" color="rgb(255 255 255 / 0.05)" />
                <x-decor.dots class="-bottom-4 -right-4 w-40 h-40" color="rgb(34 211 238 / 0.30)" />
                <div class="relative">
                    <p class="text-xs font-bold tracking-widest uppercase text-accent-400">Pengaduan</p>
                    <h2 class="mt-1 text-2xl md:text-3xl font-display font-bold leading-tight">Sampaikan keluhan, saran,
                        atau aspirasi Anda</h2>
                    <p class="mt-3 text-slate-200 leading-relaxed">Identitas pelapor dijamin kerahasiaannya. Setiap
                        pengaduan ditindaklanjuti sesuai SOP DPMPTSP.</p>
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('pengaduan.lapor') }}"
                            class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold bg-white text-primary-900 hover:bg-slate-100 transition">
                            Lapor Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('pengaduan.tracking') }}"
                            class="text-sm font-semibold text-white/90 hover:text-white">Tracking pengaduan →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
