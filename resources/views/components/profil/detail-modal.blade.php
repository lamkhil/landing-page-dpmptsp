{{--
    Generic detail modal. Relies on an Alpine scope in the surrounding section
    that defines: open (bool), i (int), items (array). Each item supports:
      { eyebrow?, title, desc?,
        sasaran?: [string], indikator?: [string],          // reformasi areas
        agents?: [{name, nip?, position?, role?, photo?}],  // reformasi areas
        agentsNote?: {label, url?},                          // SK ZI reference
        children?: [{name, desc?}], docs?: [{label, url}] }
    Sections render only when their key is present, so pages that omit them are
    unaffected. Pair with `x-effect="document.documentElement.style.overflow =
    open ? 'hidden' : ''"` on the same scope to lock background scroll.
--}}
<div x-cloak x-show="open" x-transition.opacity
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-[60] bg-primary-950/80 backdrop-blur-sm grid place-items-center p-4"
    role="dialog" aria-modal="true" aria-label="Detail">
    <div @click="open = false" class="absolute inset-0"></div>
    <div x-show="open" x-transition
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-auto overscroll-contain">
        <div class="bg-gradient-to-br from-primary-700 to-primary-900 text-white p-6 relative overflow-hidden">
            <x-decor.dots class="-top-4 -right-4 w-28 h-28" color="rgb(34 211 238 / 0.30)" />
            <button type="button" @click="open = false"
                class="absolute top-3 right-3 z-10 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 grid place-items-center transition" aria-label="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <p class="relative text-[11px] font-bold tracking-[0.2em] uppercase text-accent-400" x-text="items[i].eyebrow || 'Detail'"></p>
            <h3 class="relative mt-1 text-xl font-display font-bold pr-10" x-text="items[i].title"></h3>
        </div>
        <div class="p-6 space-y-6">
            <p class="text-sm text-ink leading-relaxed" x-show="items[i].desc" x-text="items[i].desc"></p>

            {{-- Sasaran / Program (reformasi areas) --}}
            <div x-show="items[i].sasaran && items[i].sasaran.length">
                <p class="heading-eyebrow">Sasaran / Program</p>
                <ul class="mt-3 space-y-2">
                    <template x-for="(s, si) in items[i].sasaran" :key="si">
                        <li class="flex items-start gap-2.5">
                            <svg class="mt-0.5 w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75" /></svg>
                            <span class="text-sm text-ink leading-relaxed" x-text="s"></span>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- Indikator Keberhasilan (reformasi areas) --}}
            <div x-show="items[i].indikator && items[i].indikator.length">
                <p class="heading-eyebrow">Indikator Keberhasilan</p>
                <ul class="mt-3 space-y-2">
                    <template x-for="(ind, ii) in items[i].indikator" :key="ii">
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-accent-500 shrink-0"></span>
                            <span class="text-sm text-ink leading-relaxed" x-text="ind"></span>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- Agen Perubahan (reformasi areas) --}}
            <div x-show="items[i].agents && items[i].agents.length" class="pt-2 border-t border-slate-100">
                <p class="heading-eyebrow">Agen Perubahan</p>
                <p class="mt-1 text-xs text-muted" x-show="items[i].agentsNote">
                    Sesuai
                    <template x-if="items[i].agentsNote && items[i].agentsNote.url">
                        <a :href="items[i].agentsNote.url" target="_blank" rel="noopener"
                            class="font-semibold text-primary-700 hover:underline" x-text="items[i].agentsNote.label"></a>
                    </template>
                    <template x-if="items[i].agentsNote && !items[i].agentsNote.url">
                        <span class="font-semibold text-ink" x-text="items[i].agentsNote.label"></span>
                    </template>
                </p>
                <ul class="mt-3 grid sm:grid-cols-2 gap-3">
                    <template x-for="(ag, ai) in items[i].agents" :key="ai">
                        <li class="flex items-center gap-3 rounded-xl border border-slate-100 p-3">
                            <template x-if="ag.photo">
                                <img :src="ag.photo" :alt="ag.name" class="w-11 h-11 rounded-full object-cover shrink-0" />
                            </template>
                            <template x-if="!ag.photo">
                                <span class="w-11 h-11 rounded-full bg-primary-50 text-primary-700 grid place-items-center shrink-0 text-xs font-bold font-display"
                                    x-text="ag.name.split(' ').filter(Boolean).slice(0, 2).map(w => w[0]).join('').toUpperCase()"></span>
                            </template>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-ink truncate" x-text="ag.name"></p>
                                <p class="text-xs text-muted truncate" x-show="ag.nip" x-text="'NIP ' + ag.nip"></p>
                                <p class="text-xs text-muted truncate" x-show="ag.position" x-text="ag.position"></p>
                                <span class="mt-1 inline-block text-[10px] font-bold tracking-wide uppercase px-1.5 py-0.5 rounded bg-accent-500/15 text-primary-800"
                                    x-show="ag.role" x-text="ag.role"></span>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- Sub-units (tim kerja) --}}
            <div x-show="items[i].children && items[i].children.length">
                <p class="heading-eyebrow">Tim Kerja</p>
                <ul class="mt-3 space-y-3">
                    <template x-for="(t, ti) in items[i].children" :key="ti">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 w-6 h-6 rounded-lg bg-primary-50 text-primary-700 grid place-items-center shrink-0 text-xs font-bold font-display" x-text="ti + 1"></span>
                            <div>
                                <p class="text-sm font-semibold text-ink" x-text="t.name"></p>
                                <p class="text-xs text-muted leading-relaxed mt-0.5" x-show="t.desc" x-text="t.desc"></p>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- Related documents --}}
            <div x-show="items[i].docs && items[i].docs.length" class="pt-2 border-t border-slate-100">
                <p class="heading-eyebrow">Dokumen Terkait</p>
                <div class="mt-3 flex flex-col gap-2">
                    <template x-for="(d, di) in items[i].docs" :key="di">
                        <a :href="d.url"
                            class="group inline-flex items-center justify-between gap-2 rounded-xl border border-slate-100 px-4 py-2.5 hover:border-primary-200 hover:bg-primary-50/50 transition">
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-ink">
                                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span x-text="d.label"></span>
                            </span>
                            <svg class="w-4 h-4 text-muted group-hover:text-primary-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
