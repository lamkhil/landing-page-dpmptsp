@props([
    'items' => [],          // ['label'=>'Berita', 'title'=>'Judul', 'date'=>Carbon|null, 'url'=>'#']
    'badge' => 'Info Terkini',
    'speed' => 60,          // pixels per second
])

@php
    $items = collect($items)->filter(fn ($i) => ! empty($i['title']))->values();
@endphp

@if ($items->isNotEmpty())
    <div class="relative overflow-hidden bg-primary-950 border-y border-white/10"
         x-data="marquee({{ (int) $speed }})"
         @mouseenter="paused = true"
         @mouseleave="paused = false"
         aria-label="{{ $badge }}">
        <div class="flex items-stretch">
            {{-- Leading badge (sticky-feeling, sits above scroll) --}}
            <div class="shrink-0 flex items-center gap-2 bg-accent-500 text-primary-950 px-4 py-2.5 font-bold text-[11px] tracking-[0.2em] uppercase z-20 shadow-[8px_0_16px_-8px_rgb(0_0_0_/_0.4)]">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-950 animate-pulse"></span>
                {{ $badge }}
            </div>

            {{-- Viewport: narrow flex item, clips overflow.
                 `min-w-0` is critical — without it, flex-1 grows to fit the (huge) track. --}}
            <div class="relative flex-1 min-w-0 overflow-hidden">
                {{-- Fade masks --}}
                <div class="pointer-events-none absolute inset-y-0 left-0 w-12 bg-gradient-to-r from-primary-950 to-transparent z-10"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-12 bg-gradient-to-l from-primary-950 to-transparent z-10"></div>

                {{-- Track: rendered twice so when offset reaches halfWidth, the next copy is already in view. --}}
                <div x-ref="track" class="flex w-max will-change-transform">
                    @for ($pass = 0; $pass < 2; $pass++)
                        <ul class="flex items-center gap-8 px-8 py-2.5 text-sm text-slate-100 whitespace-nowrap shrink-0"
                            @if ($pass === 1) aria-hidden="true" @endif>
                            @foreach ($items as $item)
                                @php
                                    $labelColor = match ($item['label'] ?? null) {
                                        'Berita'      => 'bg-accent-500/20 text-accent-300',
                                        'Pengumuman'  => 'bg-emerald-500/20 text-emerald-300',
                                        'Agenda'      => 'bg-amber-500/20 text-amber-300',
                                        default       => 'bg-white/15 text-slate-200',
                                    };
                                @endphp
                                <li class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase {{ $labelColor }}">
                                        {{ $item['label'] ?? 'Info' }}
                                    </span>
                                    <a href="{{ $item['url'] ?? '#' }}" class="hover:text-accent-300 transition">
                                        {{ $item['title'] }}
                                    </a>
                                    @if (! empty($item['date']))
                                        <span class="text-xs text-slate-400">— {{ \Illuminate\Support\Carbon::parse($item['date'])->translatedFormat('d M Y') }}</span>
                                    @endif
                                    <span class="text-slate-500 select-none">•</span>
                                </li>
                            @endforeach
                        </ul>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endif
