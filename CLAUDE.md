# DPMPTSP LANDING PAGE + CMS SYSTEM SPECIFICATION

## PROJECT OVERVIEW

Buat website resmi DPMPTSP modern berbasis:

* Laravel 13
* TailwindCSS
* Alpine.js
* FilamentPHP
* PostgreSQL
* Redis
* Queue System

Konsep:

* Government enterprise website
* Modern public service portal
* WBK/WBBM ready
* Clean architecture
* CMS driven
* Scalable modular system
* Mobile-first responsive
* High performance
* SEO optimized

Referensi UI:

* https://rsudbdh.surabaya.go.id
* https://dpmptsp.badungkab.go.id

Namun desain harus lebih modern, minimalis, dan enterprise.

---

# CORE PRINCIPLES

## SYSTEM RULE

PENTING:
Pisahkan dengan jelas:

1. STATIC SYSTEM
2. CMS MANAGED CONTENT

JANGAN membuat semua hal editable dari CMS.

Rule utama:

* Struktur/layout = STATIC
* Isi/content/data = CMS

Karena:

* lebih aman
* maintainable
* scalable
* minim error admin
* konsisten UI/UX

---

# STATIC SYSTEM (HARDCODED)

Bagian ini TIDAK editable dari CMS.

## STATIC RULES

### Layout System

Hardcoded:

* navbar structure
* footer layout
* responsive grid
* sidebar mobile
* search overlay
* breadcrumbs
* pagination
* modal system
* toast notification
* loading skeleton
* dark mode system
* animation system

### Routing

Hardcoded:

* route pages
* route groups
* middleware
* auth
* permissions
* API structure

### Dashboard Engine

Hardcoded:

* chart containers
* chart component
* tabs
* statistic cards
* realtime counter engine

Only datasets editable from CMS.

### GIS / Map Engine

Hardcoded:

* map renderer
* GIS engine
* heatmap engine
* polygon rendering
* clustering
* layer system

Only data editable from CMS.

### System Infrastructure

Hardcoded:

* RBAC
* queue system
* cache system
* SEO engine
* API architecture
* analytics integration
* activity log
* audit trail
* storage engine
* security middleware
* rate limiting
* backup system

---

# FRONTEND NAVBAR STRUCTURE

## 1. Beranda

Landing page utama.

Section:

* Hero Banner
* Statistik Singkat
* Highlight Layanan
* Aplikasi Publik
* Dashboard Statistik
* Berita
* Survey Kepuasan
* Zona Integritas

---

## 2. Profil

Submenu:

* Profil DPMPTSP
* Visi & Misi
* Struktur Organisasi
* Tugas & Fungsi
* Maklumat Pelayanan
* SOP Pelayanan
* Standar Pelayanan
* Reformasi Birokrasi
* Zona Integritas
* WBK/WBBM
* FAQ

---

## 3. Layanan

Submenu:

* Perizinan Berusaha
* Non Perizinan
* OSS
* Tracking Perizinan
* Konsultasi Online
* Pengaduan
* Antrian Online
* Persyaratan Perizinan
* Download Formulir
* SLA Pelayanan

---

## 4. Aplikasi Publik

Submenu:

* SIPEBA
* E-Perizinan
* OSS
* Tracking Izin
* Dashboard Investasi
* Peta Potensi
* Survey IKM
* SP4N LAPOR
* CCTV Pelayanan
* Open Data
* Chatbot Pelayanan

RULE:
Semua aplikasi publik berasal dari CMS.

JANGAN hardcode daftar aplikasi.

Gunakan:

* dynamic application cards
* category support
* sorting
* featured app
* external/internal URL
* app status

---

## 5. Statistik

Submenu:

* Dashboard Investasi
* Dashboard Perizinan
* Statistik PMA/PMDN
* Statistik Perizinan
* Statistik Kepuasan
* SLA Pelayanan
* Grafik Tahunan
* Grafik Bulanan
* Open Data Statistik

RULE:

* layout dashboard static
* data dashboard editable via CMS/API

Gunakan:

* line chart
* bar chart
* donut chart
* animated counter
* realtime stats

---

## 6. Informasi Publik

Submenu:

* Berita
* Pengumuman
* Agenda
* Artikel
* Regulasi
* Dokumen Publik
* Infografis
* LKjIP
* Renstra
* Laporan Tahunan
* Download Center

---

## 7. Pengaduan

Submenu:

* SP4N LAPOR
* Whistleblowing System
* Pengaduan Online
* Tracking Pengaduan
* Survey Kepuasan
* Konsultasi Masyarakat

---

## 8. Kontak

Submenu:

* Kontak Kami
* Lokasi Kantor
* Jam Pelayanan
* Call Center
* Media Sosial
* Live Chat

---

# CTA BUTTONS

Static:

* Ajukan Perizinan
* Tracking Izin

Admin hanya dapat mengubah:

* URL
* label
* visibility

---

# CMS MANAGED CONTENT

Semua berikut editable via CMS.

---

# CMS MODULES

## 1. Dashboard

* overview statistics
* quick access
* visitor analytics
* activity summary

---

## 2. Hero Management

Editable:

* title
* subtitle
* background image/video
* CTA buttons
* running text
* highlight cards

---

## 3. Menu Management

Editable:

* menu labels
* submenu labels
* sorting
* visibility
* icon
* external links

RULE:

* admin tidak boleh membuat route baru
* admin hanya mengubah data menu

---

## 4. Aplikasi Publik Management

Editable:

* app name
* description
* icon/logo
* thumbnail
* category
* URL
* app type
* app status
* featured status
* sorting
* publish status

Support:

* external link
* internal page
* API integration

---

## 5. Statistik Management

Editable:

* chart dataset
* labels
* yearly statistics
* monthly statistics
* PMA
* PMDN
* SLA
* satisfaction score
* counters
* infographic stats

RULE:
Chart component tetap static.

---

## 6. Peta Potensi Management

Editable:

* region data
* marker
* polygon
* investment data
* categories
* popup information
* map layer visibility

RULE:
Map engine tetap static.

---

## 7. Informasi Publik Management

Editable:

* berita
* pengumuman
* agenda
* artikel
* regulasi
* dokumen
* infografis

Features:

* category
* tags
* SEO
* thumbnail
* scheduled publish
* draft/publish
* featured content

---

## 8. Zona Integritas Management

Editable:

* WBK/WBBM content
* anti gratification content
* service declaration
* integrity evidence
* survey
* innovation content

---

## 9. Media Management

Editable:

* images
* videos
* documents
* galleries
* banners

Support:

* drag and drop upload
* media optimization
* image compression

---

## 10. FAQ Management

Editable:

* question
* answer
* category
* sorting

---

## 11. Testimoni & Survey

Editable:

* testimonials
* satisfaction index
* ratings
* survey charts

---

## 12. Footer Management

Editable:

* address
* contact
* social media
* quick links
* map embed
* office hours

---

## 13. SEO Management

Editable:

* meta title
* meta description
* keywords
* OG image
* structured data
* robots
* sitemap settings

---

## 14. User & Permission

Features:

* roles
* permissions
* activity log
* audit trail

Roles:

* super admin
* admin
* editor
* operator
* viewer

---

# CMS RULES

## IMPORTANT RULES

### DO NOT

* jangan jadikan layout editable
* jangan jadikan route editable
* jangan jadikan chart structure editable
* jangan jadikan map engine editable
* jangan jadikan navbar structure editable

### ONLY EDIT

* content
* data
* labels
* media
* statistics
* links
* publish status

---

# FRONTEND UI RULES

## DESIGN STYLE

* modern government
* enterprise feel
* clean layout
* minimal
* premium
* not flashy
* not colorful overload

---

## COLOR PALETTE

Dominant:

* blue
* white
* gray

Accent:

* cyan
* navy

Avoid:

* neon colors
* excessive gradients
* gaming style UI

---

## COMPONENT STYLE

Use:

* rounded-xl
* soft shadow
* spacing consistent
* large typography hierarchy
* card-based layout

Avoid:

* cramped layout
* excessive animation
* too many colors

---

# PERFORMANCE RULES

Target:

* Lighthouse 90+
* lazy loading
* image optimization
* Redis cache
* queue heavy jobs
* responsive images
* CDN ready

---

# SECURITY RULES

Implement:

* CSRF protection
* XSS sanitization
* upload validation
* role permission
* activity logging
* rate limiting
* secure headers

---

# ARCHITECTURE

Use:

* service layer
* repository pattern
* modular architecture
* reusable components
* clean code
* SOLID principle

---

# DATABASE RULES

Separate:

* content tables
* media tables
* statistics tables
* settings tables
* audit tables

Avoid:

* giant generic table
* storing everything in JSON

---

# FINAL GOAL

Website harus:

* terlihat modern
* profesional
* cepat
* mudah dipakai masyarakat
* siap presentasi WBK/WBBM
* scalable jangka panjang
* mudah maintenance
* enterprise grade
* CMS friendly
* future proof


# DATA SOURCE RULE

Gunakan website resmi DPMPTSP Surabaya sebagai referensi utama konten dan struktur informasi:

[DPMPTSP Surabaya](https://dpm-ptsp.surabaya.go.id/?utm_source=chatgpt.com)

---

# CONTENT SOURCE POLICY

Semua data awal/default content diambil dan disesuaikan dari website resmi tersebut, meliputi:

* profil instansi
* visi misi
* struktur organisasi
* layanan
* informasi publik
* berita
* regulasi
* kontak
* maklumat pelayanan
* SOP
* standar pelayanan
* pengaduan
* zona integritas
* reformasi birokrasi
* statistik publik yang tersedia

Namun implementasi UI/UX harus dibuat:

* lebih modern
* lebih clean
* lebih cepat
* lebih enterprise
* mobile-first
* lebih profesional
* dashboard oriented
* siap WBK/WBBM

---

# IMPORTANT IMPLEMENTATION RULE

## JANGAN COPY PASTE WEBSITE LAMA

Gunakan hanya sebagai:

* referensi data
* referensi informasi
* referensi struktur organisasi
* referensi layanan

JANGAN:

* meniru UI lama
* meniru layout lama
* meniru styling lama
* meniru struktur HTML lama

---

# DATA MIGRATION RULE

## INITIAL SEEDER

Buat initial seeder/importer untuk:

* menu
* profil
* berita
* layanan
* aplikasi publik
* statistik
* FAQ
* footer
* kontak

Agar setelah install:
website langsung memiliki konten awal.

---

# CMS CONTENT RULE

Setelah data awal masuk:
semua content harus editable via CMS.

Admin harus dapat:

* edit
* tambah
* hapus
* publish/unpublish
* schedule publish

tanpa perlu edit kode.

---

# SEO RULE

Pertahankan:

* slug SEO friendly
* struktur informasi publik
* metadata penting
* heading hierarchy

Tambahkan:

* OpenGraph
* schema markup
* sitemap dynamic
* meta management

---

# APPLICATION DATA RULE

Menu:
“Aplikasi Publik”

harus dinamis dari CMS.

Contoh default:

* SIPEBA
* OSS
* Dashboard Investasi
* Peta Potensi
* SP4N LAPOR
* Survey IKM
* E-Perizinan
* Tracking Izin

Namun admin bebas:

* tambah aplikasi
* nonaktifkan aplikasi
* ubah urutan
* ubah icon/banner

---

# STATISTICS RULE

Dashboard statistik harus support:

* manual input
* import CSV/Excel
* API ready

Data:

* PMA
* PMDN
* jumlah izin
* SLA
* kepuasan masyarakat
* realtime counters

Chart layout tetap static.

---

# DESIGN DIRECTION

Gunakan style:

* Kemenkeu modern dashboard
* government enterprise
* clean analytics
* premium card layout
* informative but minimal

Hindari:

* terlalu ramai
* terlalu banyak warna
* animasi berlebihan
* gaya template admin

---

# FINAL TARGET

Website harus terasa seperti:
“Portal layanan publik pemerintahan digital modern”

Bukan:

* blog biasa
* template sekolah
* template admin dashboard
* company profile sederhana

Harus:

* profesional
* informatif
* transparan
* cepat
* modern
* siap pengembangan jangka panjang
