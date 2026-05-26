<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Agenda;
use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Impor berita dari arsip resmi DPM-PTSP Kota Surabaya.
 *
 * Sumber: laporan "DETAIL BERITA" (sso/reportberita.php), disimpan sebagai
 * fixture HTML di database/seeders/data/berita-surabaya.html. Hanya baris
 * berkelompok "BERITA" yang diimpor sebagai type=news. Gambar di-hotlink ke
 * server sumber (cover_path menyimpan URL absolut, di-resolve via Post::cover_url).
 *
 * Bersifat OVERWRITE: seluruh post type=news dihapus lalu diisi ulang dari arsip.
 */
class BeritaImportSeeder extends Seeder
{
    private const SOURCE_BASE = 'https://dpm-ptsp.surabaya.go.id/';

    public function run(): void
    {
        $file = database_path('seeders/data/berita-surabaya.html');
        if (! is_file($file)) {
            $this->command?->warn('  ! fixture berita-surabaya.html tidak ditemukan — skip impor berita');
            return;
        }

        $author = \App\Models\User::query()->first();

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.file_get_contents($file));
        libxml_clear_errors();
        $xp = new DOMXPath($dom);
        $rows = $xp->query('//table[contains(@class,"table")]//tbody/tr');

        // Kategori (slug kategori 'post') untuk tiap kelompok artikel.
        $catId = fn (?string $slug) => $slug
            ? Category::where(['type' => 'post', 'slug' => $slug])->value('id')
            : null;
        $artikelCat = [
            'ARTIKEL PERIZINAN'       => 'perizinan',
            'ARTIKEL PENANAMAN MODAL' => 'investasi',
        ];

        // OVERWRITE: hapus agenda arsip (yang terkait post) lebih dulu, lalu
        // seluruh post berita/pengumuman/artikel lama. Agenda milik AgendaSeeder
        // (post_id null) tidak tersentuh.
        $oldIds = Post::query()
            ->whereIn('type', [Post::TYPE_NEWS, Post::TYPE_ANNOUNCEMENT, Post::TYPE_ARTICLE])
            ->withTrashed()->pluck('id');
        Agenda::query()->whereIn('post_id', $oldIds)->delete();
        Post::query()
            ->whereIn('type', [Post::TYPE_NEWS, Post::TYPE_ANNOUNCEMENT, Post::TYPE_ARTICLE])
            ->withTrashed()->forceDelete();

        // Cegah tabrakan slug dengan post lain (profil, infografis, dll).
        $used = Post::query()->withTrashed()->pluck('slug')->flip()->all();
        $counts = [Post::TYPE_NEWS => 0, Post::TYPE_ANNOUNCEMENT => 0, Post::TYPE_ARTICLE => 0];
        $agendaCount = 0;

        foreach ($rows as $tr) {
            $tds = $xp->query('.//td', $tr);
            if ($tds->length < 5) {
                continue;
            }
            $kelompok = $this->clean($tds->item(0)->textContent);
            $isArtikel = str_starts_with($kelompok, 'ARTIKEL');
            if (! $isArtikel && $kelompok !== 'BERITA') {
                continue;
            }

            $title = $this->clean($tds->item(1)->textContent);
            if ($title === '') {
                continue;
            }
            $title = mb_substr($title, 0, 255);

            $cover    = $this->absUrl($this->firstImg($xp, $tds->item(2)));
            $body     = $this->cleanBody($this->innerHtml($dom, $tds->item(2)));
            $bodyText = $this->clean(strip_tags($body));
            $excerpt  = Str::limit($bodyText, 220);
            $date     = $this->parseDate(trim($tds->item(4)->textContent));

            // Tentukan tipe + kategori + (opsional) jadwal acara.
            $schedule = null;
            if ($isArtikel) {
                $type = Post::TYPE_ARTICLE;
                $catSlug = $artikelCat[$kelompok] ?? null;
            } elseif ($this->looksLikeInvitation(mb_strtolower($title.' '.$bodyText))) {
                // Undangan/sosialisasi/pemberitahuan → Pengumuman.
                $type = Post::TYPE_ANNOUNCEMENT;
                $catSlug = 'pengumuman';
                $schedule = $this->parseSchedule($bodyText); // hanya bila ada tanggal acara
            } else {
                $type = Post::TYPE_NEWS;
                $catSlug = null;
            }

            // Slug unik & stabil (lintas seluruh tabel posts).
            $slug = Str::limit(Str::slug($title) ?: 'konten', 200, '');
            $base = $slug;
            $i = 2;
            while (isset($used[$slug])) {
                $slug = $base.'-'.$i++;
            }
            $used[$slug] = true;

            $post = Post::create([
                'type'         => $type,
                'category_id'  => $catId($catSlug),
                'author_id'    => $author?->id,
                'title'        => $title,
                'slug'         => $slug,
                'excerpt'      => $excerpt !== '' ? $excerpt : null,
                'body'         => $body !== '' ? $body : '<p>'.e($title).'</p>',
                'cover_path'   => $cover,
                'status'       => Post::STATUS_PUBLISHED,
                'is_featured'  => false, // headline halaman otomatis ambil yang terbaru
                'view_count'   => 0,
                'published_at' => $date,
            ]);
            $counts[$type]++;

            // Acara berjadwal → buat Agenda yang TERKAIT ke pengumuman ini.
            if ($schedule !== null) {
                Agenda::create([
                    'post_id'      => $post->id,
                    'title'        => $title,
                    'organizer'    => 'DPM-PTSP Kota Surabaya',
                    'starts_at'    => $schedule,
                    'is_published' => true,
                ]);
                $agendaCount++;
            }
        }

        $this->command?->info(sprintf(
            '  ✓ impor %d berita, %d pengumuman, %d artikel, +%d agenda dari arsip DPM-PTSP Surabaya',
            $counts[Post::TYPE_NEWS], $counts[Post::TYPE_ANNOUNCEMENT], $counts[Post::TYPE_ARTICLE], $agendaCount
        ));
    }

    private function firstImg(DOMXPath $xp, DOMNode $cell): ?string
    {
        $imgs = $xp->query('.//img', $cell);
        return $imgs->length ? $imgs->item(0)->getAttribute('src') : null;
    }

    private function innerHtml(DOMDocument $dom, DOMNode $node): string
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $dom->saveHTML($child);
        }
        return $html;
    }

    /** Bersihkan body: buang gambar, normalkan jadi paragraf. */
    private function cleanBody(string $html): string
    {
        $html = preg_replace('/<img\b[^>]*>/i', '', $html);          // buang gambar
        $html = preg_replace('/^(\s|<br\s*\/?>)+/i', '', (string) $html); // buang <br>/spasi awal
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        // Bila tak ada <p>, ubah baris/<br> jadi paragraf.
        if (stripos($html, '<p') === false) {
            $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
            $parts = preg_split('/\n+/', $html);
            $paras = [];
            foreach ($parts as $p) {
                $p = trim($p);
                if ($p !== '') {
                    $paras[] = '<p>'.$p.'</p>';
                }
            }
            $html = implode('', $paras);
        }

        return $html;
    }

    private function absUrl(?string $src): ?string
    {
        if (! $src) {
            return null;
        }
        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
            return $src;
        }
        return self::SOURCE_BASE.ltrim($src, './');
    }

    private function parseDate(string $raw): Carbon
    {
        try {
            return Carbon::createFromFormat('d-m-Y', $raw)->startOfDay();
        } catch (\Throwable) {
            return now();
        }
    }

    private function clean(string $s): string
    {
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $s = str_replace("\xC2\xA0", ' ', $s); // &nbsp; → spasi biasa
        return trim(preg_replace('/\s+/u', ' ', $s) ?? '');
    }

    /** Penanda khas undangan/sosialisasi/pemberitahuan (presisi tinggi). */
    private function looksLikeInvitation(string $haystack): bool
    {
        foreach (['meeting id', 'passcode', 'akan dilaksanakan pada', 'buruan join', 'join lur', 'tanpa dipungut biaya'] as $kw) {
            if (str_contains($haystack, $kw)) {
                return true;
            }
        }
        return false;
    }

    /** Ambil tanggal (+jam) acara dari body, format Indonesia. Null bila tak ada tanggal. */
    private function parseSchedule(string $text): ?Carbon
    {
        $months = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6,
            'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'nopember' => 11, 'desember' => 12,
        ];
        if (! preg_match('/(\d{1,2})\s+('.implode('|', array_keys($months)).')\s+(\d{4})/i', $text, $m)) {
            return null;
        }
        $hour = 9;
        $min = 0;
        if (preg_match('/pukul\s*:?\s*(\d{1,2})[.:](\d{2})/i', $text, $t)) {
            $hour = min(23, (int) $t[1]);
            $min = min(59, (int) $t[2]);
        }
        try {
            return Carbon::create((int) $m[3], $months[mb_strtolower($m[2])], (int) $m[1], $hour, $min, 0);
        } catch (\Throwable) {
            return null;
        }
    }
}
