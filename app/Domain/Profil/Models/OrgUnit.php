<?php

namespace App\Domain\Profil\Models;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\Regulation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * A node in the DPMPTSP organisational structure. Self-referencing: tim kerja
 * (CAT_TIM_KERJA) nest under a Bidang/Sekretariat via parent_id. Each unit can
 * relate to Regulation (dasar hukum) and Document records for the detail modal.
 */
class OrgUnit extends Model
{
    public const CAT_PIMPINAN    = 'pimpinan';
    public const CAT_SEKRETARIAT = 'sekretariat';
    public const CAT_BIDANG      = 'bidang';
    public const CAT_FUNGSIONAL  = 'fungsional';
    public const CAT_TIM_KERJA   = 'tim_kerja';

    public const CATEGORIES = [
        self::CAT_PIMPINAN    => 'Pimpinan',
        self::CAT_SEKRETARIAT => 'Sekretariat',
        self::CAT_BIDANG      => 'Bidang',
        self::CAT_FUNGSIONAL  => 'Kelompok Jabatan Fungsional',
        self::CAT_TIM_KERJA   => 'Tim Kerja',
    ];

    protected $fillable = ['parent_id', 'name', 'category', 'description', 'sort_order', 'is_published'];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
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
}
