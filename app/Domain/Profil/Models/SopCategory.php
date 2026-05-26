<?php

namespace App\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A manageable category for SOP documents (e.g. SOP MPP, SOP Pelayanan,
 * SOP Difabel). CRUD-able via Filament so the list can be arranged freely.
 */
class SopCategory extends Model
{
    protected $fillable = ['name', 'description', 'sort_order', 'is_published'];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    public function sops(): HasMany
    {
        return $this->hasMany(Sop::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
