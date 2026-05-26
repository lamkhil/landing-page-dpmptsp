<?php

namespace App\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A node in the layanan tree (mirrors SSW Alfa). Self-referencing via parent_id
 * for a full multi-level hierarchy (kategori → sub-kategori → layanan). A node
 * with children acts as a group; a leaf carries the per-service sections.
 * COMPONENTS defines the section order + labels for the public page and CMS form.
 */
class ServiceStandard extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'sort_order', 'is_published',
        'persyaratan', 'alur_perizinan', 'dasar_hukum', 'unduh', 'durasi', 'kontak',
        'retribusi', 'maklumat', 'visi_misi', 'motto',
    ];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    /**
     * column => label, in SSW Alfa display order. Maklumat/Visi-Misi/Motto are
     * GLOBAL content (Profil pages): the per-service column overrides if set,
     * otherwise the page falls back to the global value (see
     * ProfilController::standarDetail). Stored per-service only when overridden.
     */
    public const COMPONENTS = [
        'persyaratan'    => 'Persyaratan',
        'alur_perizinan' => 'Alur Perizinan',
        'dasar_hukum'    => 'Dasar Hukum',
        'unduh'          => 'Unduh Dokumen',
        'durasi'         => 'Durasi Pemrosesan',
        'kontak'         => 'Kontak',
        'retribusi'      => 'Retribusi',
        'maklumat'       => 'Maklumat Pelayanan',
        'visi_misi'      => 'Visi & Misi',
        'motto'          => 'Motto',
    ];

    /** Components that fall back to global Profil content when not overridden. */
    public const GLOBAL_COMPONENTS = ['maklumat', 'visi_misi', 'motto'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Filled sections as an ordered [label, content] list (blank ones skipped).
     *
     * @return array<int,array{label:string,content:string}>
     */
    public function components(): array
    {
        $out = [];
        foreach (self::COMPONENTS as $key => $label) {
            $value = trim((string) ($this->{$key} ?? ''));
            if ($value !== '') {
                $out[] = ['label' => $label, 'content' => $value];
            }
        }

        return $out;
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
