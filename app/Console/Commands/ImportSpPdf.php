<?php

namespace App\Console\Commands;

use App\Domain\Profil\Models\ServiceStandard;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

/**
 * Imports Standar Pelayanan from the official PDF (sp.pdf, 1294 pages, 2025).
 *
 * The PDF is text-extractable. Each layanan has a "STANDAR PELAYANAN" block
 * (Lampiran/Nomor SK + nama) with a 2-column "NO | KOMPONEN | URAIAN" table.
 * We map components → ServiceStandard columns:
 *   Persyaratan → persyaratan, Sistem/Mekanisme/Prosedur → alur_perizinan,
 *   Jangka Waktu → durasi, Biaya/Tarif → retribusi, Dasar Hukum → dasar_hukum.
 * Categories come from the Daftar Isi (SK number → kategori), services grouped
 * under their kategori via the Nomor SK in each block.
 *
 * The 2-column layout is parsed by detecting the content-column indent per block.
 * Quality is ~90% (label cells are vertically centered, causing minor bleed);
 * everything is CMS-editable afterwards.
 */
class ImportSpPdf extends Command
{
    protected $signature = 'sp:import-pdf
        {--file=sp.pdf : Path PDF relatif ke base path}
        {--limit= : Batasi jumlah blok layanan (untuk uji)}
        {--fresh : Kosongkan ServiceStandard sebelum impor}
        {--dry-run : Tampilkan tanpa menyimpan}
        {--fill-retribusi : Jangan buat data baru; isi kolom Retribusi yang kosong pada ServiceStandard yang sudah ada, dicocokkan via nama (dari Biaya di PDF)}';

    protected $description = 'Impor Standar Pelayanan per layanan dari PDF resmi (sp.pdf).';

    /**
     * ALL component labels (regex) for boundary detection — so a component
     * stops absorbing text when the next labeled row begins. Only KEEP columns
     * are persisted; the rest (produk/kontak/sarana/…) are boundaries only.
     */
    private const LABELS = [
        'durasi'         => '/jangka waktu/i',
        'retribusi'      => '/\bbiaya\b|tarif/i',
        'produk'         => '/produk/i',
        'kontak'         => '/penanganan|pengaduan|saran/i',
        'persyaratan'    => '/persyaratan/i',
        'alur_perizinan' => '/sistem|mekanisme|prosedur/i',
        'dasar_hukum'    => '/dasar hukum/i',
        'sarana'         => '/sarana/i',
        'kompetensi'     => '/kompetensi/i',
        'pengawasan'     => '/pengawasan/i',
        'jumlah'         => '/jumlah pelaksana/i',
        'jaminan'        => '/jaminan/i',
        'evaluasi'       => '/evaluasi/i',
    ];

    /** Columns actually stored on ServiceStandard. */
    private const KEEP = ['persyaratan', 'alur_perizinan', 'durasi', 'retribusi', 'dasar_hukum'];

    public function handle(): int
    {
        $dry  = (bool) $this->option('dry-run');
        $path = base_path($this->option('file'));
        if (! is_file($path)) {
            $this->error("File tidak ditemukan: {$path}");

            return self::FAILURE;
        }

        $this->info('Mengekstrak teks PDF (pdftotext -layout)…');
        $text = $this->pdfToText($path);
        if ($text === '') {
            $this->error('Gagal mengekstrak teks (pdftotext tersedia?).');

            return self::FAILURE;
        }

        $pages = explode("\f", $text);
        $this->line('  '.count($pages).' halaman.');

        // Pisahkan: halaman TOC (sebelum blok detail pertama) vs blok detail.
        $blocks = $this->splitBlocks($pages);
        $detail = $blocks['blocks'];
        $this->line('  '.count($detail).' blok layanan.');

        // Mode kombinasi: isi Retribusi (dari Biaya PDF) ke data yang sudah ada.
        if ($this->option('fill-retribusi')) {
            return $this->fillRetribusi($detail, $dry);
        }

        if ($this->option('fresh') && ! $dry) {
            ServiceStandard::query()->delete();
            $this->warn('ServiceStandard dikosongkan (--fresh).');
        }

        $limit      = $this->option('limit') !== null ? (int) $this->option('limit') : null;
        $catNode    = [];               // nama kategori => ServiceStandard
        $catSort    = 0;
        $currentCat = 'Layanan Lainnya';
        $done       = 0;

        foreach ($detail as $i => $blockText) {
            if ($limit !== null && $done >= $limit) {
                break;
            }
            $svc = $this->parseBlock($blockText);
            if ($svc['name'] === '') {
                continue;
            }
            $hasContent = count(array_filter($svc['components'])) > 0;

            // Halaman pembatas kategori: tanpa komponen, nama diawali "LAYANAN ...".
            // Nama kategori = bagian sebelum preamble SK (Kepala Dinas/Menimbang/dll).
            if (! $hasContent) {
                if (preg_match('/^LAYANAN\b/i', $svc['name'])) {
                    $raw = preg_split('/\b(Kepala Dinas|Menimbang|Nomor\s*:|Kesatu|Standar Pelayanan)\b/iu', $svc['name'])[0];
                    $currentCat = $this->titleCase($raw);
                }

                continue;
            }
            $done++;

            if ($dry) {
                $this->line(sprintf('  [%s] %s  → %s', $currentCat, Str::limit($svc['name'], 60), implode(',', array_keys(array_filter($svc['components'])))));

                continue;
            }

            if (! isset($catNode[$currentCat])) {
                $catNode[$currentCat] = ServiceStandard::updateOrCreate(
                    ['parent_id' => null, 'name' => $currentCat],
                    ['sort_order' => ++$catSort, 'is_published' => true],
                );
            }

            ServiceStandard::updateOrCreate(
                ['parent_id' => $catNode[$currentCat]->id, 'name' => Str::limit($svc['name'], 250, '')],
                array_merge($svc['components'], ['sort_order' => $i + 1, 'is_published' => true]),
            );
        }

        $this->newLine();
        $this->info(($dry ? '[dry-run] ' : '')."Selesai — {$done} layanan diproses.");

        return self::SUCCESS;
    }

    /**
     * Combine mode: fill empty `retribusi` on existing ServiceStandard rows
     * using the PDF's (clean) Biaya text, matched by normalized service name.
     */
    private function fillRetribusi(array $blocks, bool $dry): int
    {
        $map = [];
        foreach ($blocks as $b) {
            $svc = $this->parseBlock($b);
            $r   = trim($svc['components']['retribusi'] ?? '');
            if ($svc['name'] === '' || $r === '') {
                continue;
            }
            $key = $this->normalizeName($svc['name']);
            if ($key !== '' && ! isset($map[$key])) {
                $map[$key] = $r;
            }
        }
        $this->line('  '.count($map).' layanan ber-Retribusi dari PDF.');

        $leaves   = ServiceStandard::whereNotNull('parent_id')->get();
        $filled   = 0;
        $unmatched = 0;
        foreach ($leaves as $s) {
            if (trim((string) $s->retribusi) !== '') {
                continue;
            }
            $n = $this->normalizeName($s->name);
            $r = $map[$n] ?? $this->matchContains($n, $map);
            if ($r === null) {
                $unmatched++;

                continue;
            }
            if (! $dry) {
                $s->retribusi = $r;
                $s->save();
            }
            $filled++;
        }

        $this->info(($dry ? '[dry-run] ' : '')."Retribusi terisi: {$filled} | tak cocok: {$unmatched} (dari ".$leaves->count().' layanan).');

        return self::SUCCESS;
    }

    private function normalizeName(string $s): string
    {
        $s = mb_strtolower($s, 'UTF-8');
        $s = preg_replace('/^(izin baru|perpanjangan|perubahan|pelayanan|rangkaian pelayanan)\s+/u', '', $s);
        $s = preg_replace('/[^a-z0-9]+/u', ' ', $s);

        return trim(preg_replace('/\s+/', ' ', $s));
    }

    /** @param array<string,string> $map */
    private function matchContains(string $n, array $map): ?string
    {
        if (mb_strlen($n) < 12) {
            return null;
        }
        foreach ($map as $k => $v) {
            if (str_contains($k, $n) || str_contains($n, $k)) {
                return $v;
            }
        }

        return null;
    }

    private function pdfToText(string $path): string
    {
        $p = new Process(['pdftotext', '-layout', $path, '-']);
        $p->setTimeout(300);
        $p->run();

        return $p->isSuccessful() ? $p->getOutput() : '';
    }

    /**
     * Split pages into TOC (before first "STANDAR PELAYANAN" lampiran) + detail
     * blocks (each starts on a page with Nomor SK + STANDAR PELAYANAN header).
     *
     * @param  array<int,string>  $pages
     * @return array{toc:array<int,string>,blocks:array<int,string>}
     */
    private function splitBlocks(array $pages): array
    {
        $toc = [];
        $blocks = [];
        $current = null;
        $started = false;

        foreach ($pages as $page) {
            $isStart = preg_match('/Nomor\s*:/i', $page) && preg_match('/STANDAR\s+PELAYANAN/i', $page);

            if ($isStart) {
                $started = true;
                if ($current !== null) {
                    $blocks[] = $current;
                }
                $current = $page;
            } elseif ($started) {
                $current .= "\f".$page; // continuation page of current block
            } else {
                $toc[] = $page;
            }
        }
        if ($current !== null) {
            $blocks[] = $current;
        }

        return ['toc' => $toc, 'blocks' => $blocks];
    }

    /**
     * @return array{name:string,sk:string,sk_key:string,components:array<string,string>}
     */
    private function parseBlock(string $block): array
    {
        $lines = explode("\n", str_replace("\f", "\n", $block));

        // SK number.
        $sk = '';
        $skKey = '';
        foreach ($lines as $l) {
            if (preg_match('#Nomor\s*:?\s*((\d{3})/(\d+)/[0-9.]+\w*/\d{4})#i', $l, $m)) {
                $sk = $m[1];
                $skKey = $m[2].'/'.$m[3];
                break;
            }
        }

        // Name: lines after "STANDAR PELAYANAN" until the table/section header.
        $name = '';
        for ($i = 0; $i < count($lines); $i++) {
            if (preg_match('/STANDAR\s+PELAYANAN/i', $lines[$i])) {
                $parts = [];
                for ($j = $i + 1; $j < count($lines); $j++) {
                    $t = trim($lines[$j]);
                    if ($t === '') {
                        continue;
                    }
                    if (preg_match('/^(NO\b|KOMPONEN|URAIAN|Service Delivery|Penyampaian)/i', $t)) {
                        break;
                    }
                    $parts[] = $t;
                    if (count($parts) >= 3) {
                        break;
                    }
                }
                $name = $this->cleanName(implode(' ', $parts));
                break;
            }
        }

        return [
            'name'       => $name,
            'sk'         => $sk,
            'sk_key'     => $skKey,
            'components' => $this->parseComponents($lines),
        ];
    }

    /**
     * Extract component columns from a block's lines using the detected
     * content-column indent.
     *
     * @param  array<int,string>  $lines
     * @return array<string,string>
     */
    private function parseComponents(array $lines): array
    {
        // Match a left-column cell (after stripping a leading "1." / "2)") to a
        // label. Labels are SHORT — reject long cells (those are content
        // sentences that merely contain a keyword, e.g. "…dinyatakan lengkap").
        $labelOf = function (string $cell): ?string {
            $c = trim(preg_replace('/^\s*\d+[.)]?\s*/', '', trim($cell)));
            if ($c === '' || mb_strlen($c) > 40 || ! preg_match('/[A-Za-z]/', $c)) {
                return null;
            }
            foreach (self::LABELS as $key => $re) {
                if (preg_match($re, $c)) {
                    return $key;
                }
            }

            return null;
        };

        // Content-column threshold: labels sit left of it, content at/after it.
        $indents = [];
        foreach ($lines as $l) {
            if (preg_match('/^(\s*)(\S.*)$/', $l, $m)) {
                $ind = strlen($m[1]);
                if ($ind >= 12 && $ind <= 40 && mb_strlen(trim($m[2])) > 30) {
                    $indents[$ind] = ($indents[$ind] ?? 0) + 1;
                }
            }
        }
        ksort($indents);
        $col = $indents ? max(14, (int) array_key_first($indents)) : 18;

        $out = array_fill_keys(array_keys(self::LABELS), '');
        $cur = null;
        foreach ($lines as $l) {
            $t = trim($l);
            if ($t === '') {
                continue;
            }
            // Skip table/section headers.
            if (preg_match('/^(NO\s+KOMPONEN|KOMPONEN\s+URAIAN|Service Delivery|Manufacturing|Penyampaian Layanan|Pengelolaan Layanan)/i', $t)) {
                continue;
            }

            $indent = strlen($l) - strlen(ltrim($l));
            $cells  = preg_split('/\s{2,}/', $t);
            $content = $t;

            if ($indent < $col) {                       // label-column zone
                // "NO" number may be its own cell → label is the next cell.
                $idx = preg_match('/^\d+[.)]?$/', trim($cells[0] ?? '')) ? 1 : 0;
                $key = $labelOf($cells[$idx] ?? '');
                if ($key !== null) {                    // a labeled row begins
                    $cur     = $key;
                    $content = trim(implode(' ', array_slice($cells, $idx + 1)));
                } elseif (count($cells) <= 1) {
                    continue;                           // stray label-continuation word
                }
            }
            if ($cur !== null && $content !== '') {
                $out[$cur] .= $content."\n";
            }
        }

        $result = [];
        foreach (self::KEEP as $k) {
            $v = $this->tidy($out[$k] ?? '');
            // Short value fields: drop a leaked leading "NO" number.
            if (in_array($k, ['durasi', 'retribusi'], true)) {
                $v = trim(preg_replace('/^\d+\s+/', '', $v));
            }
            $result[$k] = $v;
        }

        return $result;
    }

    private function cleanName(string $s): string
    {
        $s = preg_replace('/\s+/', ' ', $s);
        $s = trim($s, " .;:-\t");

        return trim($s);
    }

    private function titleCase(string $s): string
    {
        $s = mb_convert_case(mb_strtolower($this->cleanName($s), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
        // Kecilkan kata sambung umum.
        return preg_replace_callback('/\b(Dan|Serta|Atau|Untuk|Di|Ke|Dari|Yang)\b/u', fn ($m) => mb_strtolower($m[1], 'UTF-8'), $s);
    }

    private function tidy(string $s): string
    {
        $lines = array_filter(array_map('trim', explode("\n", $s)), fn ($l) => $l !== '');

        return trim(implode("\n", $lines));
    }
}
