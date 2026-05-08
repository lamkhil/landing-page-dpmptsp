# DPMPTSP Surabaya — Landing Page + CMS

Portal resmi Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surabaya. Modern government enterprise portal dengan arsitektur CMS-driven, layout/route static, dan dashboard statistik publik.

> **Architectural rule** (dari `CLAUDE.md`): _structure/layout = STATIC; content/data = CMS_. Admin tidak dapat menambah route, mengubah navbar, atau memodifikasi chart engine — hanya data yang mereka render.

## Tech stack

- **PHP 8.4** + **Laravel 13.8**
- **Filament v5** (single admin panel `/admin`)
- **TailwindCSS v4** + **Alpine.js v3**
- **PostgreSQL 16** (production) — pdo_pgsql terpasang
- **Redis** (cache/queue/session production), database driver default untuk dev
- **ApexCharts** (lazy-loaded), **Leaflet** (Phase 5b)
- **Spatie**: laravel-permission, laravel-activitylog, laravel-medialibrary, laravel-sluggable, laravel-honeypot, laravel-sitemap

## Setup

### 1. Database

Buat database PostgreSQL + user:

```sql
CREATE USER dpmptsp WITH PASSWORD 'isi-disini';
CREATE DATABASE dpmptsp_landing OWNER dpmptsp;
GRANT ALL PRIVILEGES ON DATABASE dpmptsp_landing TO dpmptsp;
```

Isi `DB_PASSWORD` di `.env`.

### 2. Install & migrate

```bash
composer install
npm install
npm run build         # atau `npm run dev` untuk hot-reload
php artisan key:generate    # jika APP_KEY masih kosong
php artisan storage:link
php artisan migrate --seed
```

Seeder akan menampilkan kredensial super-admin di console (di env `local`: `superadmin@dpmptsp.surabaya.go.id` / `dpmptsp123`). Login: `http://127.0.0.1:8000/admin`

### 3. Run

```bash
php artisan serve
# di terminal lain:
npm run dev
# untuk queue worker (opsional di dev):
php artisan queue:work
```

### Production (singkat)

```bash
APP_ENV=production
APP_DEBUG=false
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

Lalu jalankan:

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
npm run build
```

Supervisor untuk queue worker (template Phase 7).

## Arsitektur

```
app/
├── Domain/               # Modular: Hero/, Menu/, Application/, Statistic/, Region/,
│                         #          Content/ (post, agenda, regulation, document, category, tag),
│                         #          Faq/, Survey/, Footer/, Seo/, Complaint/
│   └── <Module>/
│       ├── Models/
│       ├── Repositories/    # interface + Eloquent impl (binding di DomainServiceProvider)
│       ├── Services/        # cache + business logic
│       ├── DTOs/            # typed payloads (in-progress)
│       └── Listeners/       # cache invalidation
├── Filament/             # admin panel resources (Phase 4)
├── Http/Controllers/Public/   # 8 navbar sections
├── Providers/
│   ├── AppServiceProvider.php           # super-admin gate, model strict-mode
│   ├── DomainServiceProvider.php        # repository contract bindings
│   └── Filament/AdminPanelProvider.php  # /admin panel config
└── Models/User.php       # FilamentUser + HasRoles + LogsActivity
```

`config/dpmptsp.php` adalah satu-satunya source-of-truth untuk:

- whitelist named-routes (admin Menu CMS hanya boleh memilih dari sini)
- definisi `statistic_groups` (PMA, PMDN, izin, SLA, IKM)
- daftar role RBAC
- TTL cache per modul
- limit submission publik

Mengubah file ini = perubahan kode (intentional barrier).

## Status build

Branch ini adalah **scaffold + foundation** sesuai plan multi-phase (lihat `.claude/plans/resilient-growing-pumpkin.md`).

| Phase | Scope | Status |
|---|---|---|
| 1 | Scaffold Laravel 13 + Filament 5 + npm + Tailwind v4 + Alpine + ApexCharts; routes (8 sections, ~50 named routes); base layout + navbar + footer + home + placeholder | ✅ Selesai |
| 2 | 25 migrations CMS (hero, menu, applications, statistics, regions, posts, agendas, regulations, documents, faqs, testimonials, surveys, complaints, footer, SEO) | ✅ Selesai |
| 3 | 25 Eloquent models dengan traits (HasSlug, LogsActivity, SoftDeletes); contoh lengkap layer Application (Repository interface + Eloquent impl + Service + cache invalidation listener); DomainServiceProvider | ✅ Selesai (Application jadi template; modul lain perlu repo+service serupa di Phase berikutnya) |
| 6 | 9 seeders: RolePermissionSeeder (40+ permissions × 5 roles), DefaultUserSeeder, MenuSeeder (8 navbar + 60+ submenu), HeroSeeder, ApplicationSeeder (10 default apps), StatisticSeeder (groups + 3-tahun data + 4 counters), FaqSeeder, FooterSeeder, SeoDefaultsSeeder | ✅ Selesai |
| 4 | Filament Resources untuk semua entitas (~20 resources), policies, widgets dashboard | ⏳ Belum — fondasi siap |
| 5 | Halaman publik penuh (8 navbar sections) terhubung Service layer; ApexCharts pada dashboard statistik; Leaflet untuk Peta Potensi | ⏳ Belum — placeholder pages aktif |
| 7 | Security headers, rate-limit middleware, sitemap dynamic, image conversion pipeline, Lighthouse CI, queue worker config | ⏳ Belum — basic CSRF + throttle:5,1 sudah di routes |

Phase 1, 2, 3, 6 sudah cukup untuk: login admin, lihat schema lengkap, navigasi 8 section, build asset, dan deploy dev dengan data awal sesuai DPMPTSP Surabaya.

## Verifikasi cepat

```bash
# Routes
php artisan route:list --except-vendor | wc -l   # ~50 route

# PHP lint seluruh kode
find app database -name "*.php" -exec php -l {} \; | grep -v "No syntax"

# Asset build
npm run build

# Smoke test setelah migrate --seed
php artisan serve &
curl -fsS http://127.0.0.1:8000/ | head -5
curl -fsS http://127.0.0.1:8000/admin/login | grep -i 'DPMPTSP CMS'
```

## CLAUDE.md rules — ringkas

✅ Implemented:
- 8-section navbar **hardcoded** (`routes/web.php`); CMS hanya edit label/icon/order via Menu Resource
- Route `route_name` di tabel `menus` tervalidasi terhadap whitelist `config('dpmptsp.menu_routes')` (admin tidak bisa ketik route arbitrary)
- Tabel terpisah per concern (no JSON catch-all); semua FK di-index, soft-delete pada audit-relevant tables
- RBAC 5 roles + 40+ permissions per resource × ability
- Activity log pada modul audit-sensitive (Hero, Menu, Application, Post, FooterSetting, SeoSetting, Complaint, User)
- Tampilan: gov blue palette, Inter + Plus Jakarta Sans, rounded-2xl, soft shadow, no neon, no gradient overload, mobile-first

⏳ Belum diterapkan (Phase 4/5/7):
- Filament Resources GUI (login Filament sudah jalan tapi nav group masih kosong)
- Public pages dengan data CMS (saat ini placeholder)
- Lighthouse 90+ verification
- Image conversion pipeline (medialibrary belum dikonfigurasi conversions)

## Lisensi

Proprietary — Pemerintah Kota Surabaya, DPMPTSP.
