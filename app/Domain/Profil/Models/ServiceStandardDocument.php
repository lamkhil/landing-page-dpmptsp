<?php

namespace App\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Official Standar Pelayanan document for a given year (covers all services).
 * file_path may be null until the admin uploads that year's document.
 */
class ServiceStandardDocument extends Model
{
    protected $fillable = ['year', 'title', 'file_path', 'sort_order', 'is_published'];

    protected $casts = [
        'year'         => 'integer',
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }
}
