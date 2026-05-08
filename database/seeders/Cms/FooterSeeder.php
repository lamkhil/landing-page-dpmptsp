<?php

namespace Database\Seeders\Cms;

use App\Domain\Footer\Models\FooterLink;
use App\Domain\Footer\Models\FooterSetting;
use Illuminate\Database\Seeder;

/**
 * Footer + contact info from the official site dpm-ptsp.surabaya.go.id.
 * Address, phone, email — verbatim from the source. Office hours are not
 * published on the source site, so we use the standard Pemkot Surabaya
 * service hours; admin can adjust via /admin/manage-footer.
 */
class FooterSeeder extends Seeder
{
    public function run(): void
    {
        FooterSetting::singleton()->update([
            'address'       => 'Mal Pelayanan Publik Lt.3, Jl. Tunjungan No. 1-3 Genteng, Surabaya 60275',
            'phone'         => '+62 (031) 99243924',
            'email'         => 'dpm-ptsp@surabaya.go.id',
            'office_hours'  => 'Senin–Jumat · 08.00–16.00 WIB · Layanan online 24 jam',
            'embed_map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.16!2d112.7378!3d-7.2575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1',
            'social_links'  => [
                ['platform' => 'facebook',  'url' => 'https://www.facebook.com/dpmptsp.surabaya'],
                ['platform' => 'instagram', 'url' => 'https://www.instagram.com/dpmptspsby/'],
                ['platform' => 'twitter',   'url' => 'https://twitter.com/dpmptsp_sby'],
                ['platform' => 'youtube',   'url' => 'https://www.youtube.com/@dpmptspkotasurabaya'],
            ],
            'about_text'    => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surabaya — lembaga yang menyelenggarakan pelayanan perizinan terpadu satu pintu serta pengembangan iklim investasi di Kota Surabaya.',
        ]);

        $links = [
            ['group' => 'quick',    'label' => 'Profil',           'url' => '/profil',           'sort_order' => 0],
            ['group' => 'quick',    'label' => 'Visi & Misi',      'url' => '/profil/visi-misi', 'sort_order' => 1],
            ['group' => 'quick',    'label' => 'Layanan',          'url' => '/layanan',          'sort_order' => 2],
            ['group' => 'quick',    'label' => 'Aplikasi Publik',  'url' => '/aplikasi-publik',  'sort_order' => 3],
            ['group' => 'quick',    'label' => 'Informasi Publik', 'url' => '/informasi',        'sort_order' => 4],

            ['group' => 'service',  'label' => 'Lapor Pengaduan',  'url' => '/pengaduan/lapor',  'sort_order' => 0],
            ['group' => 'service',  'label' => 'Tracking Pengaduan','url' => '/pengaduan/tracking','sort_order' => 1],
            ['group' => 'service',  'label' => 'SP4N LAPOR!',      'url' => 'https://www.lapor.go.id', 'open_in_new_tab' => true, 'sort_order' => 2],
            ['group' => 'service',  'label' => 'SIPPN MENPAN-RB',  'url' => 'https://sippn.menpan.go.id/beranda', 'open_in_new_tab' => true, 'sort_order' => 3],
            ['group' => 'service',  'label' => 'Whistleblowing',   'url' => '/pengaduan/whistleblowing', 'sort_order' => 4],

            ['group' => 'partner',  'label' => 'OSS RBA',          'url' => 'https://oss.go.id', 'open_in_new_tab' => true, 'sort_order' => 0],
            ['group' => 'partner',  'label' => 'SSW Alfa Surabaya','url' => 'https://sswalfa.surabaya.go.id', 'open_in_new_tab' => true, 'sort_order' => 1],
            ['group' => 'partner',  'label' => 'Mal Pelayanan Publik Sby', 'url' => 'https://mpp.surabaya.go.id', 'open_in_new_tab' => true, 'sort_order' => 2],
            ['group' => 'partner',  'label' => 'Pemkot Surabaya',  'url' => 'https://surabaya.go.id', 'open_in_new_tab' => true, 'sort_order' => 3],
            ['group' => 'partner',  'label' => 'BKPM RI',          'url' => 'https://bkpm.go.id', 'open_in_new_tab' => true, 'sort_order' => 4],
        ];

        foreach ($links as $l) {
            FooterLink::updateOrCreate(
                ['group' => $l['group'], 'label' => $l['label']],
                $l + ['is_visible' => true]
            );
        }
    }
}
