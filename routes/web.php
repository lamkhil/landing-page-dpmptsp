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
    Route::get('reformasi-birokrasi',                [ProfilController::class, 'reformasi'])->name('reformasi');
    Route::get('zona-integritas',                    [ProfilController::class, 'zonaIntegritas'])->name('zi');
    Route::get('wbk-wbbm',                           [ProfilController::class, 'wbkWbbm'])->name('wbk');
    Route::get('mengapa-surabaya',                   [ProfilController::class, 'mengapaSurabaya'])->name('mengapa');
    Route::get('faq',                                [ProfilController::class, 'faq'])->name('faq');
});

Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/',                                  [LayananController::class, 'index'])->name('index');
    Route::get('perizinan-berusaha',                 [LayananController::class, 'perizinan'])->name('perizinan');
    Route::get('non-perizinan',                      [LayananController::class, 'nonPerizinan'])->name('non-perizinan');
    Route::get('oss',                                [LayananController::class, 'oss'])->name('oss');
    Route::get('tracking',                           [LayananController::class, 'tracking'])->name('tracking');
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

Route::prefix('informasi')->name('informasi.')->group(function () {
    Route::get('/',                                  [InformasiController::class, 'index'])->name('index');
    Route::get('berita',                             [InformasiController::class, 'beritaIndex'])->name('berita.index');
    Route::get('berita/{slug}',                      [InformasiController::class, 'beritaShow'])->name('berita.show');
    Route::get('pengumuman',                         [InformasiController::class, 'pengumumanIndex'])->name('pengumuman.index');
    Route::get('pengumuman/{slug}',                  [InformasiController::class, 'pengumumanShow'])->name('pengumuman.show');
    Route::get('agenda',                             [InformasiController::class, 'agendaIndex'])->name('agenda.index');
    Route::get('artikel',                            [InformasiController::class, 'artikelIndex'])->name('artikel.index');
    Route::get('artikel/{slug}',                     [InformasiController::class, 'artikelShow'])->name('artikel.show');
    Route::get('regulasi',                           [InformasiController::class, 'regulasiIndex'])->name('regulasi.index');
    Route::get('dokumen',                            [InformasiController::class, 'dokumenIndex'])->name('dokumen.index');
    Route::get('infografis',                         [InformasiController::class, 'infografisIndex'])->name('infografis.index');
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
