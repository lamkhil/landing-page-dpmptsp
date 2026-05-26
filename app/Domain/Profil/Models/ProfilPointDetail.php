<?php

namespace App\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A structured detail line of a ProfilPoint, discriminated by `kind`:
 *   sasaran   — Sasaran / Program of a ZI area perubahan
 *   indikator — Indikator Keberhasilan of a ZI area perubahan
 * Rendered as bullet lists inside the area detail modal.
 */
class ProfilPointDetail extends Model
{
    public const KIND_SASARAN   = 'sasaran';
    public const KIND_INDIKATOR = 'indikator';

    public const KINDS = [
        self::KIND_SASARAN   => 'Sasaran / Program',
        self::KIND_INDIKATOR => 'Indikator Keberhasilan',
    ];

    protected $fillable = ['profil_point_id', 'kind', 'body', 'sort_order', 'is_published'];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    public function point(): BelongsTo
    {
        return $this->belongsTo(ProfilPoint::class, 'profil_point_id');
    }

    public function scopeKind(Builder $q, string $kind): Builder
    {
        return $q->where('kind', $kind);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
