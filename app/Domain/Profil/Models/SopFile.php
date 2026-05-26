<?php

namespace App\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A per-year version of a SOP document (e.g. the 2024 / 2025 / 2026 file).
 * file_path may be null until the admin uploads that year's PDF.
 */
class SopFile extends Model
{
    protected $fillable = ['sop_id', 'year', 'file_path', 'is_published'];

    protected $casts = [
        'year'         => 'integer',
        'is_published' => 'boolean',
    ];

    public function sop(): BelongsTo
    {
        return $this->belongsTo(Sop::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }
}
