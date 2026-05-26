<?php

namespace App\Domain\Profil\Models;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\Regulation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * A single ordered content point on a profil page, discriminated by `group`:
 *   visi        — the single vision statement (body)
 *   misi        — mission list items
 *   fokus       — strategic-focus pillars (title + body)
 *   tugas_pokok — the single main-duty statement (body)
 *   fungsi      — function list items
 * Each point can relate to Regulation/Document records for the detail modal.
 */
class ProfilPoint extends Model
{
    public const GROUP_VISI        = 'visi';
    public const GROUP_MISI        = 'misi';
    public const GROUP_FOKUS       = 'fokus';
    public const GROUP_TUGAS_POKOK = 'tugas_pokok';
    public const GROUP_FUNGSI      = 'fungsi';
    public const GROUP_MAKLUMAT    = 'maklumat';
    public const GROUP_KOMITMEN    = 'komitmen';
    public const GROUP_AREA_RB     = 'area_perubahan';
    public const GROUP_RENJA_ZI    = 'renja_zi';
    public const GROUP_SK_ZI       = 'sk_zi';
    public const GROUP_WBK         = 'wbk';
    public const GROUP_WBBM        = 'wbbm';

    public const GROUPS = [
        self::GROUP_VISI        => 'Visi',
        self::GROUP_MISI        => 'Misi',
        self::GROUP_FOKUS       => 'Fokus Strategis',
        self::GROUP_TUGAS_POKOK => 'Tugas Pokok',
        self::GROUP_FUNGSI      => 'Fungsi',
        self::GROUP_MAKLUMAT    => 'Naskah Maklumat',
        self::GROUP_KOMITMEN    => 'Komitmen Pelayanan',
        self::GROUP_AREA_RB     => 'Area Perubahan RB',
        self::GROUP_RENJA_ZI    => 'Link Renja ZI',
        self::GROUP_SK_ZI       => 'SK ZI / Agen Perubahan',
        self::GROUP_WBK         => 'WBK (Media & Dokumentasi)',
        self::GROUP_WBBM        => 'Menuju WBBM (Media & Dokumentasi)',
    ];

    protected $fillable = ['group', 'title', 'body', 'icon', 'sort_order', 'is_published'];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    /** Change agents (agen perubahan) assigned to this area perubahan. */
    public function agents(): HasMany
    {
        return $this->hasMany(ChangeAgent::class)->orderBy('sort_order');
    }

    /** Structured sasaran/indikator detail lines for this area perubahan. */
    public function details(): HasMany
    {
        return $this->hasMany(ProfilPointDetail::class)->orderBy('sort_order');
    }

    public function documents(): MorphToMany
    {
        return $this->morphToMany(Document::class, 'documentable')->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function regulations(): MorphToMany
    {
        return $this->morphToMany(Regulation::class, 'regulationable')->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeGroup(Builder $q, string $group): Builder
    {
        return $q->where('group', $group);
    }
}
