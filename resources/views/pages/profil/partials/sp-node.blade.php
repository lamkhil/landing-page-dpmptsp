{{--
    Recursive node for the Standar Pelayanan service tree.
    Expects: $node (ServiceStandard), $childrenMap (collection keyed by parent_id), $depth (int).
    A node with children renders as an expandable group; a leaf renders as a
    clickable row that opens the detail modal (calls `load(id, name)` on the
    surrounding Alpine scope, which fetches the standard sections on demand).
--}}
@php $children = $childrenMap->get($node->id, collect()); @endphp

@if ($children->isNotEmpty())
    <div x-data="{ exp: {{ $depth === 0 ? 'true' : 'false' }} }"
        @if ($depth === 0) id="{{ \Illuminate\Support\Str::slug($node->name) }}" @endif
        class="{{ $depth === 0 ? 'bg-white rounded-2xl border border-slate-100 overflow-hidden scroll-mt-24' : 'border-l-2 border-slate-100 ml-2 pl-3' }}">
        <button type="button" @click="exp = !exp"
            class="w-full flex items-center justify-between gap-3 text-left {{ $depth === 0 ? 'px-5 py-4 hover:bg-slate-50' : 'py-2.5' }} transition">
            <span class="inline-flex items-center gap-2.5 font-display font-semibold text-ink {{ $depth === 0 ? 'text-base' : 'text-sm' }}">
                <svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" /></svg>
                {{ $node->name }}
            </span>
            <span class="inline-flex items-center gap-2 text-xs text-muted shrink-0">
                <span class="chip">{{ $children->count() }}</span>
                <svg class="w-4 h-4 transition-transform" :class="exp && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </span>
        </button>
        <div x-show="exp" x-collapse.duration.200ms
            class="{{ $depth === 0 ? 'px-4 pb-4 space-y-1.5 border-t border-slate-100 pt-2' : 'space-y-1.5 pb-1' }}">
            @foreach ($children as $child)
                @include('pages.profil.partials.sp-node', ['node' => $child, 'childrenMap' => $childrenMap, 'depth' => $depth + 1])
            @endforeach
        </div>
    </div>
@else
    <button type="button" @click="load({{ $node->id }}, @js($node->name))"
        class="group w-full flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white px-4 py-3 hover:border-primary-200 hover:bg-primary-50/40 transition text-left">
        <span class="inline-flex items-center gap-2 text-sm font-medium text-ink group-hover:text-primary-700">
            <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            {{ $node->name }}
        </span>
        <span class="inline-flex items-center gap-1 text-xs font-semibold text-primary-700 shrink-0">
            Detail
            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </span>
    </button>
@endif
