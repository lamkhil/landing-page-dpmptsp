import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';
import focus from '@alpinejs/focus';

Alpine.plugin(collapse);
Alpine.plugin(intersect);
Alpine.plugin(focus);

/* Animated counter — used for hero stats and statistik dashboard cards. */
Alpine.data('counter', (target = 0, duration = 1400) => ({
    value: 0,
    started: false,
    init() {},
    start() {
        if (this.started) return;
        this.started = true;
        const start = performance.now();
        const tick = (now) => {
            const t = Math.min(1, (now - start) / duration);
            this.value = Math.round(target * (1 - Math.pow(1 - t, 3)));
            if (t < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    },
}));

/* ApexCharts wrapper. Loaded lazily so the bundle stays small for non-dashboard pages. */
Alpine.data('apexChart', (config) => ({
    chart: null,
    async init() {
        const { default: ApexCharts } = await import('apexcharts');
        this.chart = new ApexCharts(this.$el, config);
        this.chart.render();
    },
    destroy() { this.chart?.destroy(); },
}));

/* Running-text marquee — JS-driven, frame-rate independent, robust against
   prefers-reduced-motion globals. `pxPerSec` controls speed (default 60). */
Alpine.data('marquee', (pxPerSec = 60) => ({
    offset: 0,
    halfWidth: 0,
    paused: false,
    rafId: null,
    lastTs: 0,
    init() {
        const measure = () => {
            const track = this.$refs.track;
            if (! track) return;
            this.halfWidth = track.scrollWidth / 2;
        };
        measure();
        // Re-measure on resize and when fonts/images load.
        window.addEventListener('resize', measure);
        if (document.fonts?.ready) document.fonts.ready.then(measure);

        const tick = (ts) => {
            if (! this.lastTs) this.lastTs = ts;
            const dt = (ts - this.lastTs) / 1000;
            this.lastTs = ts;
            if (! this.paused && this.halfWidth > 0) {
                this.offset = (this.offset + pxPerSec * dt) % this.halfWidth;
                this.$refs.track.style.transform = `translate3d(${-this.offset}px, 0, 0)`;
            }
            this.rafId = requestAnimationFrame(tick);
        };
        this.rafId = requestAnimationFrame(tick);
    },
    destroy() { cancelAnimationFrame(this.rafId); },
}));

/* Statistik tab switcher — used on home (#04). Receives a `stats` keyed object. */
Alpine.data('statsTabs', (stats, initialKey = null) => ({
    active: initialKey || Object.keys(stats)[0],
    stats,
    chart: null,
    apex: null,
    async init() {
        try {
            const mod = await import('apexcharts');
            this.apex = mod.default;
            this.$watch('active', () => this.render());
            this.$nextTick(() => this.render());
        } catch (e) {
            console.error('[statsTabs] failed to load ApexCharts', e);
        }
    },
    render() {
        if (! this.apex) return;
        if (this.chart) { this.chart.destroy(); this.chart = null; }
        const el = this.$refs.chart;
        const stat = this.stats[this.active];
        if (! el || ! stat || ! Array.isArray(stat.values) || stat.values.length === 0) return;
        this.chart = new this.apex(el, {
            chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'Inter, system-ui, sans-serif', animations: { enabled: true, speed: 600 } },
            series: [{ name: stat.label, data: stat.values }],
            xaxis: { categories: stat.categories, labels: { style: { colors: '#64748b' } } },
            yaxis: { labels: { style: { colors: '#64748b' }, formatter: (v) => Number(v).toLocaleString('id-ID') } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
            colors: [stat.color],
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 0.6, opacityFrom: 0.35, opacityTo: 0.05 } },
            dataLabels: { enabled: false },
            markers: { size: 5, strokeWidth: 2, strokeColors: '#fff', hover: { size: 7 } },
            tooltip: { theme: 'light', y: { formatter: (v) => Number(v).toLocaleString('id-ID') + ' ' + (stat.unit || '') } },
        });
        this.chart.render();
    },
    destroy() { this.chart?.destroy(); },
}));

/* Mobile drawer + search overlay state, scoped via Alpine.store so navbar + drawer can talk. */
Alpine.store('ui', {
    drawer: false,
    search: false,
    openDrawer() { this.drawer = true; document.body.style.overflow = 'hidden'; },
    closeDrawer() { this.drawer = false; document.body.style.overflow = ''; },
    openSearch() { this.search = true; },
    closeSearch() { this.search = false; },
});

window.Alpine = Alpine;
Alpine.start();
