<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProfilController;
use App\Http\Controllers\Public\LayananController;
use App\Http\Controllers\Public\ApplicationController;
use App\Http\Controllers\Public\StatistikController;
use App\Http\Controllers\Public\InformasiController;
use App\Http\Controllers\Public\PengaduanController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\Public\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes (HARDCODED — admins cannot add or edit routes)
|--------------------------------------------------------------------------
| Per CLAUDE.md: route structure is static. Menu labels and URLs in the
| CMS bind to NAMED routes from this whitelist; admins cannot invent
| arbitrary route paths.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/',                                  [ProfilController::class, 'index'])->name('index');
    Route::get('visi-misi',                          [ProfilController::class, 'visiMisi'])->name('visi-misi');
    Route::get('struktur-organisasi',                [ProfilController::class, 'struktur'])->name('struktur');
    Route::get('tugas-fungsi',                       [ProfilController::class, 'tugasFungsi'])->name('tugas-fungsi');
    Route::get('maklumat-pelayanan',                 [ProfilController::class, 'maklumat'])->name('maklumat');
    Route::get('sop-pelayanan',                      [ProfilController::class, 'sop'])->name('sop');
    Route::get('standar-pelayanan',                  [ProfilController::class, 'standar'])->name('standar');
    Route::get('standar-pelayanan/{serviceStandard}', [ProfilController::class, 'standarDetail'])->name('standar.detail');
    Route::get('reformasi-birokrasi',                [ProfilController::class, 'reformasi'])->name('reformasi');
    // Zona Integritas, WBK & WBBM dilebur jadi tab di halaman Reformasi Birokrasi.
    // URL lama diarahkan permanen (301) ke tab terkait agar bookmark tetap valid.
    Route::redirect('zona-integritas', '/profil/reformasi-birokrasi', 301)->name('zi');
    Route::redirect('wbk-wbbm',        '/profil/reformasi-birokrasi#wbk', 301)->name('wbk');
    Route::redirect('wbbm',            '/profil/reformasi-birokrasi#wbbm', 301)->name('wbbm');
    Route::get('mengapa-surabaya',                   [ProfilController::class, 'mengapaSurabaya'])->name('mengapa');
    Route::get('inovasi',                            [ProfilController::class, 'inovasi'])->name('inovasi.index');
    Route::get('inovasi/{slug}',                     [ProfilController::class, 'inovasiShow'])->name('inovasi.show');
    Route::get('faq',                                [ProfilController::class, 'faq'])->name('faq');
});

Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/',                                  [LayananController::class, 'index'])->name('index');
    Route::get('perizinan-berusaha',                 [LayananController::class, 'perizinan'])->name('perizinan');
    Route::get('non-perizinan',                      [LayananController::class, 'nonPerizinan'])->name('non-perizinan');
    Route::get('pelayanan-non-perizinan',            [LayananController::class, 'pelayananNonPerizinan'])->name('pelayanan-non-perizinan');
    Route::get('oss',                                [LayananController::class, 'oss'])->name('oss');
    // Tracking diarahkan ke pelacakan SSW Alfa.
    Route::redirect('tracking', 'https://sswalfa.surabaya.go.id/cek/lacak')->name('tracking');
    // Kamus KBLI diarahkan ke kamus KBLI OSS (selalu terkini).
    Route::redirect('kbli', 'https://oss.go.id/id/kbli')->name('kbli');
    Route::get('konsultasi-online',                  [LayananController::class, 'konsultasi'])->name('konsultasi');
    Route::get('antrian-online',                     [LayananController::class, 'antrian'])->name('antrian');
    Route::get('persyaratan',                        [LayananController::class, 'persyaratan'])->name('persyaratan');
    Route::get('formulir',                           [LayananController::class, 'formulir'])->name('formulir');
    Route::get('sla',                                [LayananController::class, 'sla'])->name('sla');
});

Route::prefix('aplikasi-publik')->name('aplikasi.')->group(function () {
    Route::get('/',                                  [ApplicationController::class, 'index'])->name('index');
    Route::get('{slug}',                             [ApplicationController::class, 'show'])->name('show');
});

Route::prefix('statistik')->name('statistik.')->group(function () {
    Route::get('/',                                  [StatistikController::class, 'index'])->name('index');
    Route::get('investasi',                          [StatistikController::class, 'investasi'])->name('investasi');
    Route::get('perizinan',                          [StatistikController::class, 'perizinan'])->name('perizinan');
    Route::get('pma-pmdn',                           [StatistikController::class, 'pmaPmdn'])->name('pma-pmdn');
    Route::get('kepuasan',                           [StatistikController::class, 'kepuasan'])->name('kepuasan');
    Route::get('sla',                                [StatistikController::class, 'sla'])->name('sla');
    Route::get('open-data',                          [StatistikController::class, 'openData'])->name('open-data');
});

/*
 * Informasi = konten editorial (Berita, Pengumuman, Agenda, Artikel, Infografis).
 * Dokumen Publik = arsip dokumen (Regulasi, LKjIP, Renstra, Laporan Tahunan,
 * Download Center) — tampil sebagai menu top-level terpisah di navbar, namun
 * URL-nya tetap di bawah prefix /informasi agar bookmark lama tetap valid.
 */
Route::prefix('informasi')->name('informasi.')->group(function () {
    Route::get('/',                                  [InformasiController::class, 'index'])->name('index');
    Route::get('berita',                             [InformasiController::class, 'beritaIndex'])->name('berita.index');
    Route::get('berita/{slug}',                      [InformasiController::class, 'beritaShow'])->name('berita.show');
    Route::get('pengumuman',                         [InformasiController::class, 'pengumumanIndex'])->name('pengumuman.index');
    Route::get('pengumuman/{slug}',                  [InformasiController::class, 'pengumumanShow'])->name('pengumuman.show');
    Route::get('agenda',                             [InformasiController::class, 'agendaIndex'])->name('agenda.index');
    Route::get('artikel',                            [InformasiController::class, 'artikelIndex'])->name('artikel.index');
    Route::get('artikel/{slug}',                     [InformasiController::class, 'artikelShow'])->name('artikel.show');
    Route::get('infografis',                         [InformasiController::class, 'infografisIndex'])->name('infografis.index');
    Route::get('infografis/{slug}',                  [InformasiController::class, 'infografisShow'])->name('infografis.show');

    // --- Dokumen Publik (hub + sub-arsip) ---
    Route::get('dokumen-publik',                     [InformasiController::class, 'dokumenPublik'])->name('dokumen-publik');
    Route::get('regulasi',                           [InformasiController::class, 'regulasiIndex'])->name('regulasi.index');
    Route::get('dokumen',                            [InformasiController::class, 'dokumenIndex'])->name('dokumen.index');
    Route::get('lkjip',                              [InformasiController::class, 'lkjip'])->name('lkjip');
    Route::get('renstra',                            [InformasiController::class, 'renstra'])->name('renstra');
    Route::get('laporan-tahunan',                    [InformasiController::class, 'laporanTahunan'])->name('laporan-tahunan');
    Route::get('download',                           [InformasiController::class, 'downloadCenter'])->name('download');
});

Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
    Route::get('/',                                  [PengaduanController::class, 'index'])->name('index');
    Route::get('lapor',                              [PengaduanController::class, 'create'])->name('lapor');
    Route::post('lapor',                             [PengaduanController::class, 'store'])->name('store')->middleware('throttle:5,1');
    Route::get('tracking',                           [PengaduanController::class, 'trackingForm'])->name('tracking');
    Route::get('tracking/{ticket}',                  [PengaduanController::class, 'trackingShow'])->name('tracking.show');
    Route::get('sp4n-lapor',                         [PengaduanController::class, 'sp4n'])->name('sp4n');
    Route::get('whistleblowing',                     [PengaduanController::class, 'wbs'])->name('wbs');
    Route::get('konsultasi',                         [PengaduanController::class, 'konsultasi'])->name('konsultasi');
});

Route::prefix('kontak')->name('kontak.')->group(function () {
    Route::get('/',                                  [KontakController::class, 'index'])->name('index');
    Route::post('/',                                 [KontakController::class, 'store'])->name('store')->middleware('throttle:5,1');
    Route::get('lokasi',                             [KontakController::class, 'lokasi'])->name('lokasi');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
