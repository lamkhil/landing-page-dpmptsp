@php
    /** @var \App\Domain\Menu\Services\MenuService $menuService */
    $menuService = app(\App\Domain\Menu\Services\MenuService::class);

    /*
     * The 8 top-level sections are STATIC (CLAUDE.md: structure = static).
     * Sub-menus are CMS-editable via Menu Resource. A self-referential
     * submenu item (one that resolves to the same URL as the section root)
     * is filtered out defensively here so admin mistakes never surface as
     * a redundant dropdown.
     */
    $topSections = [
        ['key' => 'beranda',   'label' => 'Beranda',          'route' => 'home'],
        ['key' => 'profil',    'label' => 'Profil',           'route' => 'profil.index'],
        ['key' => 'layanan',   'label' => 'Layanan',          'route' => 'layanan.index'],
        ['key' => 'aplikasi',  'label' => 'Aplikasi',         'route' => 'aplikasi.index'],
        ['key' => 'statistik', 'label' => 'Statistik',        'route' => 'statistik.index'],
        ['key' => 'informasi', 'label' => 'Informasi',        'route' => 'informasi.index'],
        ['key' => 'dokumen',   'label' => 'Dokumen Publik',   'route' => 'informasi.dokumen-publik'],
        ['key' => 'pengaduan', 'label' => 'Pengaduan',        'route' => 'pengaduan.index'],
        ['key' => 'kontak',    'label' => 'Kontak',           'route' => 'kontak.index'],
    ];

    $resolveUrl = function ($menu) {
        if ($menu->external_url) return $menu->external_url;
        if ($menu->route_name && \Illuminate\Support\Facades\Route::has($menu->route_name)) {
            return route($menu->route_name);
        }
        return '#';
    };

    // Pre-fetch + filter submenus so the template stays clean.
    $submenus = [];
    foreach ($topSections as $s) {
        $sectionUrl = route($s['route']);
        $submenus[$s['key']] = $menuService->byGroup($s['key'])
            ->filter(fn ($item) => $resolveUrl($item) !== $sectionUrl)
            ->values();
    }
@endphp

<header x-data class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-[0_1px_2px_rgb(15_23_42_/_0.04)]">
    <nav class="container-page flex items-center justify-between gap-4 h-16" aria-label="Menu utama">
        {{-- Brand — logo resmi DPMPTSP Surabaya (sumber: dpm-ptsp.surabaya.go.id) --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
            <img src="{{ asset('brand/dpmptsp-logo-96.png') }}"
                 srcset="{{ asset('brand/dpmptsp-logo-96.png') }} 1x, {{ asset('brand/dpmptsp-logo-256.png') }} 2x"
                 width="36" height="36"
                 alt="Logo DPMPTSP Kota Surabaya"
                 class="w-9 h-9 object-contain" />
            <span class="font-display font-bold text-primary-900 leading-tight hidden sm:block">
                DPMPTSP<br><span class="text-[10px] font-medium text-muted tracking-wide">Kota Surabaya</span>
            </span>
        </a>

        {{-- Desktop nav (xl = 1280px+, ada cukup ruang untuk 8 item + CTA) --}}
        <ul class="hidden xl:flex items-center gap-0.5 text-[13px] font-medium text-ink">
            @foreach ($topSections as $section)
                @php $submenu = $submenus[$section['key']]; @endphp
                <li @class(['relative' => $submenu->isNotEmpty()])
                    @if ($submenu->isNotEmpty())
                        x-data="{ open: false }"
                        @mouseenter="open = true"
                        @mouseleave="open = false"
                    @endif>
                    <a href="{{ route($section['route']) }}"
                       class="px-2.5 py-2 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition inline-flex items-center gap-1 whitespace-nowrap">
                        {{ $section['label'] }}
                        @if ($submenu->isNotEmpty())
                            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        @endif
                    </a>

                    @if ($submenu->isNotEmpty())
                        <div x-cloak x-show="open" x-transition.opacity.duration.150ms
                             class="absolute left-0 top-full pt-2 w-64 z-50">
                            <div class="bg-white rounded-xl shadow-xl border border-slate-100 p-2 ring-1 ring-slate-200/50">
                                @foreach ($submenu as $item)
                                    @if ($item->children->isNotEmpty())
                                        {{-- Nested group: label + indented children --}}
                                        <a href="{{ $resolveUrl($item) }}"
                                           @if($item->open_in_new_tab) target="_blank" rel="noopener" @endif
                                           class="block px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wide text-primary-700 hover:text-primary-800 transition">
                                            {{ $item->label }}
                                        </a>
                                        <div class="ml-3 mb-1 pl-2 border-l border-slate-100">
                                            @foreach ($item->children as $child)
                                                <a href="{{ $resolveUrl($child) }}"
                                                   @if($child->open_in_new_tab) target="_blank" rel="noopener" @endif
                                                   class="block px-3 py-2 rounded-lg text-[13px] text-ink hover:bg-primary-50 hover:text-primary-700 transition">
                                                    {{ $child->label }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <a href="{{ $resolveUrl($item) }}"
                                           @if($item->open_in_new_tab) target="_blank" rel="noopener" @endif
                                           class="block px-3 py-2 rounded-lg text-[13px] text-ink hover:bg-primary-50 hover:text-primary-700 transition">
                                            {{ $item->label }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        {{-- CTA buttons (xl) + tablet (lg shows compact CTA only) --}}
        <div class="hidden lg:flex items-center gap-2 shrink-0">
            <a href="{{ route('layanan.tracking') }}"
               class="hidden xl:inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-[13px] font-semibold text-primary-700 hover:bg-primary-50 transition">
                Tracking
            </a>
            <a href="{{ route('layanan.perizinan') }}"
               class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-[13px] font-semibold bg-primary-700 text-white hover:bg-primary-800 transition shadow-sm">
                Ajukan Perizinan
            </a>
        </div>

        {{-- Mobile / tablet hamburger --}}
        <button type="button" class="xl:hidden p-2 -mr-2 rounded-lg hover:bg-primary-50 text-ink"
                @click="$store.ui.openDrawer()" aria-label="Buka menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </nav>

    {{--
        Mobile / tablet drawer — teleported to <body> so it escapes the
        header's sticky+z-40 stacking context. Otherwise siblings of the
        header (hero section, running-text marquee) with their own
        positioning/opacity can paint over a z-50 drawer trapped inside z-40.
    --}}
    <template x-teleport="body">
    <div x-cloak x-show="$store.ui.drawer"
         x-transition.opacity
         class="fixed inset-0 z-[60] xl:hidden"
         role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-slate-900/60" @click="$store.ui.closeDrawer()"></div>
        <aside class="absolute right-0 top-0 h-full w-80 max-w-[85%] bg-white shadow-xl flex flex-col"
               x-trap.inert.noscroll="$store.ui.drawer">
            <div class="flex items-center justify-between p-4 border-b border-slate-100">
                <span class="font-display font-bold text-primary-900">Menu</span>
                <button class="p-2 rounded-lg hover:bg-primary-50" @click="$store.ui.closeDrawer()" aria-label="Tutup menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M6 18L18 6"/>
                    </svg>
                </button>
            </div>
            <nav class="p-2 overflow-y-auto text-sm flex-1">
                @foreach ($topSections as $section)
                    @php $submenu = $submenus[$section['key']]; @endphp
                    <div x-data="{ open: false }" class="border-b border-slate-100 last:border-0">
                        <div class="flex items-center">
                            <a href="{{ route($section['route']) }}" class="flex-1 px-3 py-3 rounded-lg hover:bg-primary-50 font-medium">{{ $section['label'] }}</a>
                            @if ($submenu->isNotEmpty())
                                <button type="button" @click="open = !open" class="p-3 rounded-lg hover:bg-primary-50" :aria-expanded="open" aria-label="Toggle submenu">
                                    <svg class="w-4 h-4 transition" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @if ($submenu->isNotEmpty())
                            <div x-show="open" x-collapse class="pl-3 pb-2">
                                @foreach ($submenu as $item)
                                    @if ($item->children->isNotEmpty())
                                        <a href="{{ $resolveUrl($item) }}"
                                           @if($item->open_in_new_tab) target="_blank" rel="noopener" @endif
                                           class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary-700 hover:bg-primary-50">{{ $item->label }}</a>
                                        <div class="ml-3 pl-2 border-l border-slate-100">
                                            @foreach ($item->children as $child)
                                                <a href="{{ $resolveUrl($child) }}"
                                                   @if($child->open_in_new_tab) target="_blank" rel="noopener" @endif
                                                   class="block px-3 py-2 rounded-lg text-sm text-muted hover:bg-primary-50 hover:text-primary-700">{{ $child->label }}</a>
                                            @endforeach
                                        </div>
                                    @else
                                        <a href="{{ $resolveUrl($item) }}"
                                           @if($item->open_in_new_tab) target="_blank" rel="noopener" @endif
                                           class="block px-3 py-2 rounded-lg text-sm text-muted hover:bg-primary-50 hover:text-primary-700">{{ $item->label }}</a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
            <div class="p-3 border-t border-slate-100 flex flex-col gap-2 bg-slate-50">
                <a href="{{ route('layanan.tracking') }}" class="btn-outline justify-center">Tracking Izin</a>
                <a href="{{ route('layanan.perizinan') }}" class="btn-primary justify-center">Ajukan Perizinan</a>
            </div>
        </aside>
    </div>
    </template>
</header>
