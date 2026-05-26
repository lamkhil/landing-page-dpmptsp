<?php

namespace App\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A SOP belonging to a SopCategory. The actual downloadable documents are
 * per-year versions in SopFile (2024/2025/2026…), so a SOP can offer a year
 * chooser on the public page.
 */
class Sop extends Model
{
    protected $fillable = ['sop_category_id', 'title', 'description', 'doc_number', 'sort_order', 'is_published'];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SopCategory::class, 'sop_category_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(SopFile::class)->orderByDesc('year');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
