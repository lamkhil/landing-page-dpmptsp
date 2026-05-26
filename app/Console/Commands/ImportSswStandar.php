<?php

namespace App\Console\Commands;

use App\Domain\Profil\Models\ServiceStandard;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Scrapes Standar Pelayanan per layanan from SSW Alfa Surabaya into ServiceStandard.
 *
 * Flow (mirrors the public site, which loads everything via AJAX):
 *   1. GET  /info/daftar-perizinan          → CSRF token, session cookie, kategori (data-id + nama)
 *   2. POST /info/open_sub_perizinan         → daftar layanan per kategori (slug + nama)
 *   3. GET  /info/detail/{slug}              → nama layanan + opsi "peruntukan" (#select_sub → id_m_ijin)
 *   4. POST /info/load_informasi_by_sub_ijin → konten asli per peruntukan (JSON)
 *
 * Pohon yang dibentuk: Kategori → Layanan → (Peruntukan). Jika layanan hanya
 * punya satu peruntukan, kontennya disimpan langsung di node layanan (jadi daun).
 * Konten disimpan sebagai teks multi-baris (halaman standar pakai whitespace-pre-line).
 */
class ImportSswStandar extends Command
{
    protected $signature = 'ssw:import-standar
        {--category= : Hanya impor satu kategori (local_id, mis. 2)}
        {--limit= : Batasi jumlah layanan per kategori (untuk uji)}
        {--sleep=350 : Jeda antar permintaan ke SSW (milidetik)}
        {--fresh : Kosongkan semua ServiceStandard sebelum impor}
        {--dry-run : Tampilkan hasil tanpa menyimpan ke database}';

    protected $description = 'Impor Standar Pelayanan per layanan dari SSW Alfa Surabaya (scrape publik).';

    private const BASE = 'https://sswalfa.surabaya.go.id';

    /** JSON key dari load_informasi_by_sub_ijin => kolom ServiceStandard. */
    private const FIELD_MAP = [
        'syarat'        => 'persyaratan',
        'alur'          => 'alur_perizinan',
        'dasar_hukum'   => 'dasar_hukum',
        'unduh'         => 'unduh',
        'waktu'         => 'durasi',
        'identitas_opd' => 'kontak',
    ];

    private CookieJar $jar;
    private string $token = '';
    private int $sleepMs = 350;
    private bool $dry = false;

    public function handle(): int
    {
        $this->dry     = (bool) $this->option('dry-run');
        $this->sleepMs = max(0, (int) $this->option('sleep'));
        $limit         = $this->option('limit') !== null ? (int) $this->option('limit') : null;
        $onlyCat       = $this->option('category');

        $this->jar = new CookieJar();

        if ($this->option('fresh') && ! $this->dry) {
            ServiceStandard::query()->delete();
            $this->warn('ServiceStandard dikosongkan (--fresh).');
        }

        $this->info('Mengambil daftar kategori dari SSW Alfa…');
        $daftar      = $this->client()->get(self::BASE.'/info/daftar-perizinan')->body();
        $this->token = $this->extractCsrf($daftar);
        if ($this->token === '') {
            $this->error('Gagal membaca CSRF token. Dibatalkan.');

            return self::FAILURE;
        }
        $categories = $this->parseCategories($daftar);
        $this->line('  '.count($categories).' kategori ditemukan.');

        $totalCat = 0;
        $totalSvc = 0;
        $totalLeaf = 0;

        foreach ($categories as $idx => $cat) {
            if ($onlyCat !== null && (string) $cat['id'] !== (string) $onlyCat) {
                continue;
            }

            $services = $this->parseServiceLinks($this->openSub($cat['id']));
            if ($services === []) {
                $this->warn("  [{$cat['id']}] {$cat['name']} — 0 layanan");

                continue;
            }

            $catNode = $this->dry ? null : ServiceStandard::updateOrCreate(
                ['parent_id' => null, 'name' => $cat['name']],
                ['sort_order' => $idx + 1, 'is_published' => true],
            );
            $totalCat++;
            $this->info("[{$cat['id']}] {$cat['name']} — ".count($services).' layanan');

            $n = 0;
            foreach ($services as $si => $svc) {
                if ($limit !== null && $n >= $limit) {
                    break;
                }
                $n++;

                try {
                    [$svcName, $peruntukan] = $this->fetchDetail($svc['slug']);
                } catch (\Throwable $e) {
                    $this->warn("    ! gagal detail {$svc['slug']}: {$e->getMessage()}");
                    continue;
                }
                $svcName = $svcName ?: $svc['name'];
                $totalSvc++;

                // Satu peruntukan → konten langsung di node layanan (jadi daun).
                if (count($peruntukan) === 1) {
                    $content = $this->loadContent($peruntukan[0]['id'], $svc['slug']);
                    $this->upsert($catNode, $svcName, $si + 1, $content);
                    $totalLeaf++;
                    $this->detailLine($svcName, $content);

                    continue;
                }

                // Tanpa peruntukan → simpan node layanan saja (tanpa konten).
                $svcNode = $this->upsert($catNode, $svcName, $si + 1, []);
                if ($peruntukan === []) {
                    $this->line("    - {$svcName}  (tanpa peruntukan)");

                    continue;
                }

                // Banyak peruntukan → tiap peruntukan jadi daun di bawah layanan.
                $this->line("    - {$svcName}  (".count($peruntukan).' peruntukan)');
                foreach ($peruntukan as $pi => $p) {
                    $content = $this->loadContent($p['id'], $svc['slug']);
                    $this->upsert($svcNode, $p['label'], $pi + 1, $content);
                    $totalLeaf++;
                    $this->detailLine('  • '.$p['label'], $content);
                }
            }
        }

        $this->newLine();
        $this->info(($this->dry ? '[dry-run] ' : '')."Selesai — {$totalCat} kategori, {$totalSvc} layanan, {$totalLeaf} entri berstandar.");

        return self::SUCCESS;
    }

    /** @return ServiceStandard|null  null saat --dry-run */
    private function upsert(?ServiceStandard $parent, string $name, int $sort, array $content): ?ServiceStandard
    {
        if ($this->dry) {
            return null;
        }

        return ServiceStandard::updateOrCreate(
            ['parent_id' => $parent?->id, 'name' => Str::limit($name, 250, '')],
            array_merge($content, ['sort_order' => $sort, 'is_published' => true]),
        );
    }

    private function openSub(string $localId): string
    {
        return $this->client()
            ->withHeaders(['X-CSRF-TOKEN' => $this->token, 'X-Requested-With' => 'XMLHttpRequest'])
            ->asForm()
            ->post(self::BASE.'/info/open_sub_perizinan', ['local_id' => $localId])
            ->body();
    }

    /**
     * @return array{0:string,1:array<int,array{id:string,label:string}>}  [nama, peruntukan[]]
     */
    private function fetchDetail(string $slug): array
    {
        $this->nap();
        $crawler = new Crawler($this->client()->get(self::BASE.'/info/detail/'.$slug)->body());

        $h4   = $crawler->filter('.alert-text h4');
        $name = $h4->count() ? $this->clean($h4->text()) : '';

        $opts = [];
        $crawler->filter('#select_sub option')->each(function (Crawler $o) use (&$opts): void {
            $val = (string) $o->attr('value');
            if ($val !== '') {
                $opts[] = ['id' => $val, 'label' => $this->clean($o->text())];
            }
        });

        return [$name, $opts];
    }

    /** @return array<string,string> kolom ServiceStandard terisi dari JSON. */
    private function loadContent(string $idMIjin, string $slug): array
    {
        $this->nap();
        $json = $this->client()
            ->withHeaders(['X-CSRF-TOKEN' => $this->token, 'X-Requested-With' => 'XMLHttpRequest'])
            ->asForm()
            ->post(self::BASE.'/info/load_informasi_by_sub_ijin', ['datas' => $idMIjin, 'slug' => $slug])
            ->json();

        $out = [];
        if (is_array($json)) {
            foreach (self::FIELD_MAP as $key => $column) {
                $out[$column] = isset($json[$key]) ? $this->htmlToText((string) $json[$key]) : '';
            }
        }

        return $out;
    }

    private function client(): PendingRequest
    {
        return Http::withOptions(['cookies' => $this->jar])
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; DPMPTSP-Importer/1.0)'])
            ->timeout(30)
            ->retry(2, 800);
    }

    private function nap(): void
    {
        if ($this->sleepMs > 0) {
            usleep($this->sleepMs * 1000);
        }
    }

    private function detailLine(string $name, array $content): void
    {
        $filled = array_keys(array_filter($content, fn ($v) => $v !== ''));
        $this->line("    - {$name}  [".implode(', ', $filled).']');
    }

    private function extractCsrf(string $html): string
    {
        return preg_match('/name="csrf-token"\s+content="([^"]+)"/i', $html, $m) ? $m[1] : '';
    }

    /** @return array<int,array{id:string,name:string}> */
    private function parseCategories(string $html): array
    {
        $cats = [];
        (new Crawler($html))->filter('a.open_sub_izin')->each(function (Crawler $n) use (&$cats): void {
            $id = (string) $n->attr('data-id');
            if ($id === '') {
                return;
            }
            $title  = $n->filter('.kt-notification-v2__item-title');
            $cats[] = ['id' => $id, 'name' => $title->count() ? $this->clean($title->text()) : "Kategori {$id}"];
        });

        return collect($cats)->unique('id')->values()->all();
    }

    /** @return array<int,array{slug:string,name:string}> */
    private function parseServiceLinks(string $html): array
    {
        $svcs = [];
        (new Crawler($html))->filter('a[href*="/info/detail/"]')->each(function (Crawler $a) use (&$svcs): void {
            $slug = Str::of($a->attr('href') ?? '')->afterLast('/info/detail/')->before('?')->trim('/')->value();
            if ($slug === '') {
                return;
            }
            $title  = $a->filter('.card-title');
            $svcs[] = ['slug' => $slug, 'name' => $this->clean($title->count() ? $title->text() : $a->text())];
        });

        return collect($svcs)->unique('slug')->values()->all();
    }

    /** HTML (termasuk tabel/list) → teks multi-baris yang rapi. */
    private function htmlToText(string $html): string
    {
        $html = preg_replace('/<\s*li[^>]*>/i', "\n• ", $html);
        $html = preg_replace('#<\s*/\s*(td|th)\s*>#i', ' ', $html);
        $html = preg_replace('#<\s*(br|/p|/div|/tr|/h[1-6])\s*/?>#i', "\n", $html);
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Strip SSW upload-widget noise wherever it appears (incl. mid-line).
        $text = preg_replace('/\bTipe File Upload\b[^\n]*/i', '', $text);
        $text = preg_replace('/\bUnduh Contoh Dokumen\b/i', '', $text);

        $lines = array_values(array_filter(
            array_map(fn ($l) => trim(preg_replace('/[ \t]+/', ' ', $l)), preg_split('/\R+/', $text)),
            // Drop empties + SSW table/widget noise (header cells, upload widget).
            fn ($l) => $l !== '' && $l !== '•' && $l !== '#'
                && ! preg_match('/^(Tipe File Upload|Unduh)\b/i', $l)
                && ! preg_match('/^(No\.?|Nama Persyaratan|Nama Dokumen)$/i', $l),
        ));

        // SSW table cells land on separate lines ("1" then the text). Merge a
        // lone-number line with the following text → "1. Teks persyaratan".
        $merged = [];
        for ($i = 0, $n = count($lines); $i < $n; $i++) {
            if (preg_match('/^\d+$/', $lines[$i]) && isset($lines[$i + 1]) && ! preg_match('/^\d+$/', $lines[$i + 1])) {
                $merged[] = $lines[$i].'. '.$lines[$i + 1];
                $i++;
            } else {
                $merged[] = $lines[$i];
            }
        }

        return trim(implode("\n", $merged));
    }

    private function clean(string $s): string
    {
        return trim(preg_replace('/\s+/', ' ', html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }
}
