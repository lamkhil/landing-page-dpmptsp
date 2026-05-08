@php
    /** @var \App\Domain\Footer\Services\FooterService $footerService */
    $footerService = app(\App\Domain\Footer\Services\FooterService::class);
    $settings = $footerService->settings();
    $linkGroups = $footerService->groupedLinks();

    $groupLabels = [
        'quick'    => 'Tautan Cepat',
        'service'  => 'Layanan Pengaduan',
        'partner'  => 'Mitra & Eksternal',
        'external' => 'Lainnya',
    ];

    // Convert array of social links to icon list. Schema: [{platform, url}, ...]
    $socials = collect($settings->social_links ?? [])->filter(fn ($s) => isset($s['url']));
@endphp
<footer class="bg-primary-950 text-slate-200 mt-24">
    <div class="container-page py-14 grid gap-10 md:grid-cols-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="grid place-items-center w-10 h-10 rounded-xl bg-white text-primary-700 font-bold">DP</span>
                <span class="font-display font-bold text-white leading-tight">DPMPTSP<br><span class="text-xs font-normal text-slate-300">Kota Surabaya</span></span>
            </div>
            <p class="mt-4 text-sm text-slate-300 leading-relaxed">
                {{ $settings->about_text ?? 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surabaya — siap melayani perizinan dan investasi secara modern, cepat, dan transparan.' }}
            </p>

            @if ($socials->isNotEmpty())
                <div class="mt-5 flex items-center gap-3">
                    @foreach ($socials as $s)
                        <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 grid place-items-center rounded-full bg-white/10 hover:bg-white/20 transition"
                           aria-label="{{ ucfirst($s['platform'] ?? 'social') }}">
                            <span class="text-xs uppercase font-semibold text-white">{{ strtoupper(substr($s['platform'] ?? '?', 0, 2)) }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @foreach (['quick', 'service', 'partner'] as $group)
            <div>
                <h3 class="text-white font-semibold mb-3 text-sm">{{ $groupLabels[$group] ?? ucfirst($group) }}</h3>
                <ul class="space-y-2 text-sm">
                    @foreach (($linkGroups[$group] ?? []) as $link)
                        <li>
                            <a href="{{ $link->url }}"
                               @if($link->open_in_new_tab) target="_blank" rel="noopener" @endif
                               class="hover:text-white">{{ $link->label }}</a>
                        </li>
                    @endforeach
                    @if (empty($linkGroups[$group] ?? null))
                        @if ($group === 'quick')
                            <li><a href="{{ route('profil.index') }}" class="hover:text-white">Profil</a></li>
                            <li><a href="{{ route('layanan.index') }}" class="hover:text-white">Layanan</a></li>
                            <li><a href="{{ route('aplikasi.index') }}" class="hover:text-white">Aplikasi Publik</a></li>
                            <li><a href="{{ route('informasi.index') }}" class="hover:text-white">Informasi Publik</a></li>
                        @endif
                        @if ($group === 'service')
                            <li><a href="{{ route('pengaduan.lapor') }}" class="hover:text-white">Lapor Pengaduan</a></li>
                            <li><a href="{{ route('pengaduan.tracking') }}" class="hover:text-white">Tracking Pengaduan</a></li>
                            <li><a href="{{ route('pengaduan.sp4n') }}" class="hover:text-white">SP4N LAPOR</a></li>
                            <li><a href="{{ route('pengaduan.wbs') }}" class="hover:text-white">Whistleblowing</a></li>
                        @endif
                    @endif
                </ul>
            </div>
        @endforeach
    </div>

    <div class="border-t border-white/10">
        <div class="container-page py-5 grid gap-3 md:grid-cols-2 items-center text-xs text-slate-400">
            <div>
                <address class="not-italic space-y-0.5">
                    <div>{{ $settings->address ?? 'Jl. Tunjungan No. 1-3, Surabaya, Jawa Timur 60275' }}</div>
                    @if ($settings->phone)
                        <div>Tel: {{ $settings->phone }}</div>
                    @endif
                    @if ($settings->email)
                        <div>Email: <a href="mailto:{{ $settings->email }}" class="hover:text-white">{{ $settings->email }}</a></div>
                    @endif
                    @if ($settings->office_hours)
                        <div>{{ $settings->office_hours }}</div>
                    @endif
                </address>
            </div>
            <div class="flex md:justify-end items-center gap-2 flex-wrap">
                <span class="chip bg-white/10 text-white">WBK</span>
                <span class="chip bg-white/10 text-white">Menuju WBBM</span>
                <span class="chip bg-white/10 text-white">Zona Integritas</span>
            </div>
        </div>
    </div>

    <div class="bg-primary-950 border-t border-white/10">
        <div class="container-page py-3 text-[11px] text-slate-500 flex flex-col md:flex-row justify-between gap-1">
            <span>© {{ now()->year }} DPMPTSP Kota Surabaya. Hak Cipta Dilindungi.</span>
            <span>Dibangun untuk pelayanan publik modern · WBK/WBBM ready.</span>
        </div>
    </div>
</footer>
