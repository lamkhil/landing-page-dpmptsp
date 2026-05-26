<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Post;
use App\Domain\Faq\Models\Faq;
use App\Domain\Profil\Models\ChangeAgent;
use App\Domain\Profil\Models\OrgUnit;
use App\Domain\Profil\Models\ProfilPoint;
use App\Domain\Profil\Models\ProfilPointDetail;
use App\Domain\Profil\Models\ServiceStandard;
use App\Domain\Profil\Models\ServiceStandardDocument;
use App\Domain\Profil\Models\Sop;
use App\Domain\Profil\Models\SopCategory;
use App\Domain\Seo\Services\SeoService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ProfilController extends Controller
{
    /**
     * Maps each profil sub-route to a CMS Post slug. Admin manages the content
     * via Filament (Post type=profil); the route + slug pair is hardcoded here.
     */
    private const SLUG_MAP = [
        'index'           => 'profil-dpmptsp-kota-surabaya',
        'visi-misi'       => 'visi-misi-dpmptsp-kota-surabaya',
        'struktur'        => 'struktur-organisasi',
        'tugas-fungsi'    => 'tugas-fungsi',
        'maklumat'        => 'maklumat-pelayanan',
        'sop'             => 'sop-pelayanan',
        'standar'         => 'standar-pelayanan',
        'reformasi'       => 'reformasi-birokrasi',
        'mengapa-surabaya'=> 'mengapa-investasi-di-surabaya',
    ];

    public function __construct(private readonly SeoService $seo) {}

    public function index(): View         { return $this->render('Profil DPMPTSP', 'index'); }
    public function mengapaSurabaya(): View { return $this->render('Mengapa Investasi di Surabaya', 'mengapa-surabaya'); }

    /**
     * Visi & Misi — enterprise layout backed by structured CMS data
     * (ProfilPoint, grouped). All text + related documents are CRUD-able via
     * Filament (Profil → Visi/Misi/…, Dokumen & Regulasi). The Post (slug
     * visi-misi) is still used for the page title/SEO/excerpt.
     */
    public function visiMisi(): View
    {
        $post   = $this->profilPost('visi-misi');
        $points = ProfilPoint::published()
            ->whereIn('group', [ProfilPoint::GROUP_VISI, ProfilPoint::GROUP_MISI, ProfilPoint::GROUP_FOKUS])
            ->with(['regulations', 'documents'])
            ->orderBy('sort_order')
            ->get();

        $visi = $points->firstWhere('group', ProfilPoint::GROUP_VISI);

        return view('pages.profil.visi-misi', [
            'pageTitle'     => 'Visi & Misi',
            'fallbackTitle' => 'Visi & Misi',
            'seo'           => $this->seo->for('profil'),
            'post'          => $post,
            'visi'          => $visi?->body,
            'misi'          => $this->pointItems($points->where('group', ProfilPoint::GROUP_MISI), 'Misi DPMPTSP'),
            'fokus'         => $this->pointItems($points->where('group', ProfilPoint::GROUP_FOKUS), 'Fokus Strategis'),
        ]);
    }

    /**
     * Struktur Organisasi — enterprise layout backed by OrgUnit (self-referencing
     * tree; tim kerja nest under a Bidang/Sekretariat). Each unit relates to
     * Regulation/Document records. The Post (slug struktur) supplies the title,
     * intro (excerpt) and bagan image (cover).
     */
    public function struktur(): View
    {
        $post = $this->profilPost('struktur');

        $units = OrgUnit::published()
            ->whereNull('parent_id')
            ->with([
                'regulations', 'documents',
                'children' => fn ($q) => $q->published()->with(['regulations', 'documents']),
            ])
            ->orderBy('sort_order')
            ->get();

        // Leader (Pimpinan) is presented as the lead card; the rest as a grid.
        $leader = $units->firstWhere('category', OrgUnit::CAT_PIMPINAN) ?? $units->first();
        $rest   = $units->reject(fn ($u) => $leader && $u->is($leader))->values();

        return view('pages.profil.struktur', [
            'pageTitle'     => 'Struktur Organisasi',
            'fallbackTitle' => 'Struktur Organisasi',
            'seo'           => $this->seo->for('profil'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
            'chartImage'    => $post?->cover_path ? asset('storage/'.$post->cover_path) : null,
            'leader'        => $leader ? $this->unitItem($leader, 'Pimpinan') : null,
            'units'         => $rest->map(fn ($u) => $this->unitItem($u, 'Unit Kerja'))->all(),
        ]);
    }

    /**
     * Tugas & Fungsi — enterprise layout backed by structured ProfilPoint
     * (tugas_pokok statement + fungsi list), each relatable to documents.
     */
    public function tugasFungsi(): View
    {
        $post   = $this->profilPost('tugas-fungsi');
        $points = ProfilPoint::published()
            ->whereIn('group', [ProfilPoint::GROUP_TUGAS_POKOK, ProfilPoint::GROUP_FUNGSI])
            ->with(['regulations', 'documents'])
            ->orderBy('sort_order')
            ->get();

        $tugas = $points->firstWhere('group', ProfilPoint::GROUP_TUGAS_POKOK);

        return view('pages.profil.tugas-fungsi', [
            'pageTitle'     => 'Tugas & Fungsi',
            'fallbackTitle' => 'Tugas & Fungsi',
            'seo'           => $this->seo->for('profil'),
            'post'          => $post,
            'tugasPokok'    => $tugas?->body,
            'tugasDocs'     => $tugas ? $this->resolveDocs($tugas) : [],
            'fungsi'        => $this->pointItems($points->where('group', ProfilPoint::GROUP_FUNGSI), 'Fungsi DPM-PTSP'),
        ]);
    }

    /**
     * Reformasi Birokrasi — enterprise layout: the 6 ZI areas of change
     * (area perubahan / pengungkit) + a link to the Renja ZI document. All
     * CRUD-able via Filament (ReformasiResource). The Post (slug reformasi)
     * supplies the hero title/SEO/intro.
     */
    public function reformasi(): View
    {
        $post = $this->profilPost('reformasi');

        $areas = ProfilPoint::published()
            ->where('group', ProfilPoint::GROUP_AREA_RB)
            ->with([
                'regulations', 'documents',
                'agents'  => fn ($q) => $q->where('is_published', true)->orderBy('sort_order'),
                'details' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order'),
            ])
            ->orderBy('sort_order')
            ->get();

        // Renja ZI link points to an internal system Document (attached via the
        // documents relation), not an external URL.
        $renja = ProfilPoint::published()
            ->where('group', ProfilPoint::GROUP_RENJA_ZI)
            ->with(['regulations', 'documents'])
            ->first();

        // SK ZI reference (one row, like renja): title = nomor/judul SK,
        // body = document URL. Shown above each area's Agen Perubahan list.
        // The SK ZI entry carries: the document reference (title + URL) and the
        // team leadership (Ketua/Sekretaris) as its agents — team-level, not
        // tied to one area. Per-area Pokja live on each area's own agents.
        $skZi  = ProfilPoint::published()
            ->where('group', ProfilPoint::GROUP_SK_ZI)
            ->with(['regulations', 'documents', 'agents' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->first();
        $agentsNote = $this->skZiNote($skZi);

        // WBK & "Menuju WBBM" sections (below Renja): media dokumentasi
        // pelaksanaan & penilaian. Managed via Filament (ReformasiResource:
        // entri WBK / WBBM + lampiran Dokumen/Media).
        $predikat = ProfilPoint::published()
            ->whereIn('group', [ProfilPoint::GROUP_WBK, ProfilPoint::GROUP_WBBM])
            ->with('documents')
            ->get()
            ->keyBy('group');

        return view('pages.profil.reformasi', [
            'pageTitle'     => 'Reformasi Birokrasi',
            'fallbackTitle' => 'Reformasi Birokrasi',
            'seo'           => $this->seo->for('profil'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
            'areas'         => $areas->map(fn (ProfilPoint $a) => $this->areaItem($a, $agentsNote))->all(),
            'pimpinan'      => $skZi ? $this->agentItems($skZi->agents) : [],
            'skNote'        => $agentsNote,
            'renjaUrl'      => $renja ? ($this->resolveDocs($renja)[0]['url'] ?? null) : null,
            'wbk'           => $this->predikatItem($predikat->get(ProfilPoint::GROUP_WBK)),
            'wbbm'          => $this->predikatItem($predikat->get(ProfilPoint::GROUP_WBBM)),
        ]);
    }

    /**
     * Shape a WBK/WBBM ProfilPoint into a section-ready item: description +
     * attached media/documents (images render as gallery, others as doc cards).
     *
     * @return array{title:?string,body:?string,media:array<int,array{label:string,url:string,is_image:bool}>}|null
     */
    private function predikatItem(?ProfilPoint $p): ?array
    {
        if (! $p) {
            return null;
        }

        return [
            'title' => $p->title,
            'body'  => $p->body,
            'media' => $p->documents->map(fn ($d) => [
                'label'    => $d->title,
                'url'      => $d->file_path ? asset('storage/'.$d->file_path) : route('informasi.dokumen.index'),
                'is_image' => str_starts_with((string) $d->mime, 'image/'),
            ])->all(),
        ];
    }

    /**
     * Standar Pelayanan — enterprise layout: each layanan exposes the 14 standard
     * service components (clicked → detail modal), plus official Standar Pelayanan
     * documents per year. All CRUD-able via Filament (ServiceStandardResource +
     * ServiceStandardDocumentResource). The Post (slug standar) supplies the hero.
     */
    public function standar(): View
    {
        $post = $this->profilPost('standar');

        // Light columns only — the heavy section text (persyaratan, dll.) is
        // fetched per-service on click via standarDetail(), not embedded here.
        $services = ServiceStandard::published()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'parent_id', 'name', 'sort_order']);

        $documents = ServiceStandardDocument::published()
            ->orderByDesc('year')
            ->get();

        // Build the multi-level tree: roots + children keyed by parent_id.
        $roots       = $services->whereNull('parent_id')->values();
        $childrenMap = $services->whereNotNull('parent_id')->groupBy('parent_id');

        return view('pages.profil.standar', [
            'pageTitle'     => 'Standar Pelayanan',
            'fallbackTitle' => 'Standar Pelayanan',
            'seo'           => $this->seo->for('profil'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
            'services'      => $services,
            'roots'         => $roots,
            'childrenMap'   => $childrenMap,
            'documents'     => $documents,
        ]);
    }

    /**
     * JSON detail of one ServiceStandard (its filled 14-component sections),
     * loaded on demand by the Standar Pelayanan page modal — keeps the page
     * itself light even with hundreds of services.
     */
    public function standarDetail(ServiceStandard $serviceStandard): \Illuminate\Http\JsonResponse
    {
        abort_unless($serviceStandard->is_published, 404);

        $globals = $this->standarGlobals();

        $components = [];
        foreach (ServiceStandard::COMPONENTS as $key => $label) {
            $value = trim((string) ($serviceStandard->{$key} ?? ''));
            // Maklumat/Visi-Misi/Motto: fall back to global Profil content.
            if ($value === '' && in_array($key, ServiceStandard::GLOBAL_COMPONENTS, true)) {
                $value = $globals[$key] ?? '';
            }
            if ($value !== '') {
                $components[] = ['label' => $label, 'content' => $value];
            }
        }

        return response()->json([
            'name'       => $serviceStandard->name,
            'components' => $components,
        ]);
    }

    /**
     * Global Profil content used as fallback for the Maklumat / Visi-Misi /
     * Motto standar components (cached per request).
     *
     * @return array<string,string>
     */
    private function standarGlobals(): array
    {
        $maklumat = ProfilPoint::published()->where('group', ProfilPoint::GROUP_MAKLUMAT)->value('body');

        $visi = ProfilPoint::published()->where('group', ProfilPoint::GROUP_VISI)->value('body');
        $misi = ProfilPoint::published()->where('group', ProfilPoint::GROUP_MISI)->orderBy('sort_order')->pluck('body');
        $visiMisi = '';
        if ($visi) {
            $visiMisi = 'Visi:'."\n".trim($visi);
        }
        if ($misi->isNotEmpty()) {
            $visiMisi .= ($visiMisi !== '' ? "\n\n" : '').'Misi:'."\n"
                .$misi->values()->map(fn ($m, $i) => ($i + 1).'. '.trim($m))->implode("\n");
        }

        return array_filter([
            'maklumat'  => $maklumat ? trim($maklumat) : '',
            'visi_misi' => $visiMisi,
            'motto'     => '', // Motto di Profil berupa gambar; tak ada teks global.
        ], fn ($v) => $v !== '');
    }

    /**
     * SOP Pelayanan — enterprise layout: downloadable SOP documents grouped by
     * manageable category (SOP MPP, SOP Pelayanan, SOP Difabel, …). All CRUD-able
     * via Filament (SopResource + SopCategoryResource). The Post (slug sop)
     * supplies the hero title/SEO/intro.
     */
    public function sop(): View
    {
        $post = $this->profilPost('sop');

        // Each SOP carries its published per-year files (2024/2025/2026…),
        // newest year first.
        $withFiles = ['files' => fn ($q) => $q->where('is_published', true)->orderByDesc('year')];

        $categories = SopCategory::published()
            ->with(['sops' => fn ($q) => $q->where('is_published', true)->with($withFiles)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $uncategorized = Sop::published()
            ->whereNull('sop_category_id')
            ->with($withFiles)
            ->orderBy('sort_order')
            ->get();

        $total = $categories->sum(fn ($c) => $c->sops->count()) + $uncategorized->count();

        // Map keyed by SOP id → drives the year-chooser modal on click.
        $itemsById = $categories->flatMap(fn ($c) => $c->sops)->concat($uncategorized)
            ->mapWithKeys(fn (Sop $s) => [$s->id => [
                'title'    => $s->title,
                'category' => $s->category?->name ?? 'SOP Lainnya',
                'desc'     => $s->description,
                'years'    => $s->files->map(fn ($f) => [
                    'year' => $f->year,
                    'url'  => $f->file_path ? asset('storage/'.$f->file_path) : null,
                ])->values()->all(),
            ]])->all();

        return view('pages.profil.sop', [
            'pageTitle'     => 'SOP Pelayanan',
            'fallbackTitle' => 'SOP Pelayanan',
            'seo'           => $this->seo->for('profil'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
            'categories'    => $categories,
            'uncategorized' => $uncategorized,
            'totalSop'      => $total,
            'itemsById'     => $itemsById,
        ]);
    }

    /**
     * Maklumat Pelayanan — enterprise layout backed by structured ProfilPoint
     * (the maklumat pledge statement + komitmen list). The Post (slug maklumat)
     * supplies the title/SEO/intro and the official naskah image (cover).
     */
    public function maklumat(): View
    {
        $post   = $this->profilPost('maklumat');
        $points = ProfilPoint::published()
            ->whereIn('group', [ProfilPoint::GROUP_MAKLUMAT, ProfilPoint::GROUP_KOMITMEN])
            ->with(['regulations', 'documents'])
            ->orderBy('sort_order')
            ->get();

        $naskah = $points->firstWhere('group', ProfilPoint::GROUP_MAKLUMAT);

        return view('pages.profil.maklumat', [
            'pageTitle'     => 'Maklumat Pelayanan',
            'fallbackTitle' => 'Maklumat Pelayanan',
            'seo'           => $this->seo->for('profil'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
            'naskahImage'   => $post?->cover_path ? asset('storage/'.$post->cover_path) : null,
            'naskah'        => $naskah?->body,
            'naskahDocs'    => $naskah ? $this->resolveDocs($naskah) : [],
            'komitmen'      => $this->pointItems($points->where('group', ProfilPoint::GROUP_KOMITMEN), 'Komitmen Pelayanan'),
        ]);
    }

    public function inovasi(): View
    {
        $items = Post::query()
            ->ofType(Post::TYPE_INOVASI)
            ->published()
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->get();

        // Build the chip list from categories that actually have items, so
        // we never render an empty filter that yields zero results.
        $categories = $items
            ->map(fn ($p) => $p->category)
            ->filter()
            ->unique('id')
            ->sortBy('sort_order')
            ->values();

        return view('pages.profil.inovasi.index', [
            'pageTitle'  => 'Inovasi DPMPTSP',
            'seo'        => $this->seo->for('profil'),
            'items'      => $items,
            'categories' => $categories,
        ]);
    }

    public function inovasiShow(string $slug): View
    {
        $post = Post::query()
            ->ofType(Post::TYPE_INOVASI)
            ->where('slug', $slug)
            ->published()
            ->with('category')
            ->firstOrFail();

        $related = Post::query()
            ->ofType(Post::TYPE_INOVASI)
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('pages.profil.inovasi.show', [
            'pageTitle' => $post->title.' — Inovasi DPMPTSP',
            'seo'       => $this->seo->for('profil'),
            'post'      => $post,
            'related'   => $related,
        ]);
    }

    public function faq(): View
    {
        return view('pages.profil.faq', [
            'pageTitle' => 'FAQ — DPMPTSP',
            'seo'       => $this->seo->for('profil'),
            'faqs'      => Faq::query()->where('is_published', true)->with('category')->orderBy('sort_order')->get(),
            'grouped'   => Faq::query()
                ->where('is_published', true)
                ->with('category')
                ->orderBy('sort_order')
                ->get()
                ->groupBy(fn ($f) => $f->category?->name ?? 'Umum'),
        ]);
    }

    private function render(string $title, string $key): View
    {
        $post = $this->profilPost($key);

        return view('pages.profil.show', [
            'pageTitle' => $title,
            'seo'       => $this->seo->for('profil'),
            'post'      => $post,
            'fallbackTitle' => $title,
        ]);
    }

    /** Fetch the published profil Post for a SLUG_MAP key (title/SEO/intro source). */
    private function profilPost(string $key): ?Post
    {
        $slug = self::SLUG_MAP[$key] ?? null;

        return $slug
            ? Post::query()->ofType(Post::TYPE_PROFIL)->where('slug', $slug)->published()->first()
            : null;
    }

    /**
     * Shape a ProfilPoint collection into modal-ready view items.
     *
     * @return array<int,array{eyebrow:string,title:?string,body:string,icon:?string,docs:array}>
     */
    private function pointItems($points, string $eyebrow): array
    {
        return collect($points)->values()->map(fn (ProfilPoint $p) => [
            'eyebrow' => $eyebrow,
            'title'   => $p->title,
            'body'    => $p->body,
            'icon'    => $p->icon,
            'docs'    => $this->resolveDocs($p),
        ])->all();
    }

    /**
     * Shape an OrgUnit (+ its tim kerja children) into a modal-ready view item.
     *
     * @return array{name:string,category:string,description:?string,children:array,docs:array}
     */
    private function unitItem(OrgUnit $unit, string $eyebrow): array
    {
        return [
            'eyebrow'     => $eyebrow,
            'name'        => $unit->name,
            'category'    => $unit->category,
            'description' => $unit->description,
            'children'    => $unit->children->map(fn (OrgUnit $c) => [
                'name' => $c->name,
                'desc' => $c->description,
            ])->all(),
            'docs'        => $this->resolveDocs($unit),
        ];
    }

    /**
     * Shape a Reformasi area (+ its change agents and sasaran/indikator detail
     * lines) into a modal-ready view item. Dedicated to area_perubahan so the
     * shared pointItems() helper stays lean (mirrors unitItem()).
     *
     * @param  array{label:string,url:?string}|null  $agentsNote  shared SK ZI reference
     */
    private function areaItem(ProfilPoint $area, ?array $agentsNote): array
    {
        return [
            'eyebrow'    => 'Area Perubahan',
            'title'      => $area->title,
            'desc'       => $area->body,
            'sasaran'    => $area->details
                ->where('kind', ProfilPointDetail::KIND_SASARAN)
                ->pluck('body')->values()->all(),
            'indikator'  => $area->details
                ->where('kind', ProfilPointDetail::KIND_INDIKATOR)
                ->pluck('body')->values()->all(),
            'agents'     => $this->agentItems($area->agents),
            'agentsNote' => $agentsNote,
            'docs'       => $this->resolveDocs($area),
        ];
    }

    /**
     * Shape a ChangeAgent collection into modal/section-ready view items.
     *
     * @return array<int,array{name:string,nip:?string,position:?string,role:?string,photo:?string}>
     */
    private function agentItems($agents): array
    {
        return collect($agents)->map(fn (ChangeAgent $a) => [
            'name'     => $a->name,
            'nip'      => $a->nip,
            'position' => $a->position,
            'role'     => $a->role,
            'photo'    => $a->photo_path ? asset('storage/'.$a->photo_path) : null,
        ])->values()->all();
    }

    /**
     * Build the SK ZI reference line shown above the Agen Perubahan list:
     * label = nomor/judul SK, url = its document link (body URL or first doc).
     *
     * @return array{label:string,url:?string}|null
     */
    private function skZiNote(?ProfilPoint $skZi): ?array
    {
        if (! $skZi) {
            return null;
        }

        $body = trim((string) $skZi->body);
        $url  = str_starts_with($body, 'http') ? $body : ($this->resolveDocs($skZi)[0]['url'] ?? null);

        return [
            'label' => $skZi->title ?: 'Surat Keputusan Zona Integritas',
            'url'   => $url,
        ];
    }

    /**
     * Merge a model's related regulations + documents into [{label,url}] for the
     * "Dokumen Terkait" links. URL points to the uploaded file when present,
     * otherwise falls back to the relevant index page.
     *
     * @return array<int,array{label:string,url:string}>
     */
    private function resolveDocs(Model $model): array
    {
        $docs = [];

        foreach ($model->regulations as $r) {
            $docs[] = [
                'label' => $r->title,
                'url'   => $r->file_path ? asset('storage/'.$r->file_path) : route('informasi.regulasi.index'),
            ];
        }
        foreach ($model->documents as $d) {
            $docs[] = [
                'label' => $d->title,
                'url'   => $d->file_path ? asset('storage/'.$d->file_path) : route('informasi.dokumen.index'),
            ];
        }

        return $docs;
    }
}
