---
name: profil-enterprise-pattern
description: How the /profil/* enterprise pages and their per-menu CMS resources are built
metadata:
  type: project
---

All `/profil/*` pages are enterprise-styled. The hero pattern (dark gradient `from-primary-950`, `<x-decor.*>` + batik + wave, breadcrumb, accent eyebrow) is shared across all.

**Bespoke structured pages** (own controller method + view, backed by structured CMS, with a shared `<x-profil.detail-modal />`): **visi-misi, struktur-organisasi, tugas-fungsi, maklumat-pelayanan**.

**Generic pages** use `resources/views/pages/profil/show.blade.php` — upgraded (as of 2026-05-25) to an enterprise layout: hero + cover image + styled `prose` body + a sticky **profil sidebar nav** (active item highlighted via `request()->routeIs`). Covers: profil index, sop, standar, reformasi, zona-integritas, wbk-wbbm, mengapa-surabaya. These read the CMS Post (slug in `ProfilController::SLUG_MAP`); editable via the generic Posts resource.

**Structured data** (`App\Domain\Profil`):
- `OrgUnit` (`org_units`, self-referencing tree; categories pimpinan/sekretariat/bidang/fungsional/tim_kerja).
- `ProfilPoint` (`profil_points`, `group` discriminator: visi | misi | fokus | tugas_pokok | fungsi | maklumat | komitmen).
- Both `morphToMany` Document (`documentables`) + Regulation (`regulationables`) → the modal "Dokumen Terkait / Dasar Hukum" links resolve to real uploaded files.

**CMS = one menu, one resource** (user's rule; nav group **Profil**, auto-discovered):
- `VisiMisiResource` (slug visi-misi) → ProfilPoint groups visi/misi/fokus.
- `TugasFungsiResource` (slug tugas-fungsi) → groups tugas_pokok/fungsi.
- `MaklumatResource` (slug maklumat-pelayanan) → groups maklumat/komitmen.
- `OrgUnitResource` (slug org-units) → struktur.
Each filters `getEloquentQuery()` by group, uses a `group` Select + `Hidden`/title-conditional, `defaultGroup('group')` table, and document/regulation relation multi-selects. Nav group **Dokumen & Regulasi**: `RegulationResource`, `DocumentResource`.
NOTE: the user flip-flopped on CMS shape several times before settling on "1 menu = 1 resource, view handles single-vs-many"; don't reintroduce per-group resources or single-record Pages.

**Controller (`ProfilController`):** structured methods build view-model arrays via `pointItems()`, `unitItem()`, `resolveDocs()`. Profil Post still supplies title/SEO/excerpt (+ cover image for struktur & maklumat). No HTML parsing (old DOMDocument parsers + `x-profil.linkify` were removed).

**Seeders:** `ProfilStructuredSeeder` (after `RegulationSeeder` in DatabaseSeeder) seeds org_units + profil_points (incl. maklumat naskah + 5 komitmen) and attaches Perwali/Renstra/RPJMD; idempotent. `RegulationSeeder` seeds 3 regulations + 7 documents (placeholder files; admin uploads real PDFs).

**SOP Pelayanan (done):** models `Sop`, `SopCategory`, and `SopFile` (per-year version: `sops`, `sop_categories`, `sop_files`). A SOP has many `files` (one per year); `Sop` has NO single file_path (dropped). Enterprise page `pages/profil/sop.blade.php` — Alpine category filter chips + clickable cards → **year-chooser modal**: clicking a SOP opens a modal listing its years (2024/2025/2026…); each year is a download link if its file is uploaded, else "segera tersedia". Controller `sop()` groups by published category (+ "SOP Lainnya" bucket) and builds `$itemsById` (keyed by SOP id) for the modal. CMS: `SopResource` (slug `sop`) with a **creatable category select** (`->relationship('category','name')->searchable()->createOptionForm([...])` = pick or type-new inline) + an inline **Repeater `files`** (`->relationship()`, Select year + FileUpload + publish) to manage the per-year documents; `SopCategoryResource` (slug `sop-kategori`). Both nav group Profil. `SopSeeder` seeds 4 categories (SOP MPP, Pelayanan, Difabel, Pengaduan) + 8 SOPs × 3 year rows (files null until uploaded).

**Standar Pelayanan (done):** mirrors SSW Alfa as a **multi-level service tree** + yearly docs. `ServiceStandard` (`service_standards`) is self-referencing (`parent_id`) — a node with children = group/category, a leaf = layanan. Per-leaf sections (9, `COMPONENTS` const, per-layanan as the user chose — NOT shared): persyaratan, alur_perizinan, dasar_hukum, durasi, kontak, retribusi, maklumat, visi_misi, motto. (The original flat 14-component+category layout was dropped via `..._000100_restructure_service_standards_table`.) `ServiceStandardDocument` (`service_standard_documents`) = official doc per year. Page `pages/profil/standar.blade.php`: hero + yearly document cards + an **expandable tree** rendered by recursive partial `pages/profil/partials/sp-node.blade.php` (groups use `x-collapse`; leaf click → detail modal listing the filled 9 sections, numbered, `whitespace-pre-line`). Controller `standar()` passes `$roots`, `$childrenMap` (groupBy parent_id), `$itemsById` (leaves only). CMS: `ServiceStandardResource` (slug `standar-pelayanan`; form = name + **parent_id Select** (tree) + 9 looped Textareas) + `ServiceStandardDocumentResource` (slug `standar-dokumen` — year + title + PDF). `ServiceStandardSeeder` seeds a 3-level tree (Kesehatan→Fasilitas Kesehatan→Klinik/RS, etc.; 16 nodes / 9 leaves) + 3 yearly docs.

**Reformasi Birokrasi (done):** enterprise page `pages/profil/reformasi.blade.php` — the **6 ZI area perubahan** (cards → detail modal, icons by index) + a **Renja ZI** CTA. Backed by `ProfilPoint` groups `area_perubahan` (the 6 areas) and `renja_zi` (single row whose `body` holds the Renja ZI URL). Controller `reformasi()` passes `$areas` (via `pointItems`) + `$renjaUrl`. CMS: `ReformasiResource` (slug `reformasi-birokrasi`, nav Profil) manages both groups (group Select; title shown only for area; body label switches to "URL Renja ZI" for the renja_zi row). Content (6 areas) sourced from the Renja ZI Google Doc the user linked; `ProfilStructuredSeeder` seeds the 6 areas + the Renja ZI URL (https://docs.google.com/document/d/1zuM6xY-EJSVX7ENfUXAzxTDFSHBSHCGX/edit). Note: WebFetch needs the export?format=txt URL then a 2nd call following the googleusercontent redirect.

**Wave divider fix:** `components/decor/wave.blade.php` left a thin straight seam (preserveAspectRatio="none"). Fixed with `-mb-px align-bottom` + horizontal/bottom overscan in the path (`M-2,32 … L1442,121 L-2,121`). `ServiceStandardSeeder` seeds 6 example services (names adapted from SSW Alfa) + 3 yearly docs (files null).

**Navbar:** the sticky `<header>` `border-b border-slate-100` was REMOVED (user disliked the line under the header against the dark hero); the subtle `shadow-[0_1px_2px…]` stays for scroll depth.

**PENDING:** Per-menu CMS resource for the remaining generic content pages (Profil, Reformasi, ZI, WBK, Mengapa) — currently edited via the shared Posts resource; user wants each as its own menu.
