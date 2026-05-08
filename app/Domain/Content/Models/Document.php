<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = ['category_id', 'title', 'description', 'file_path', 'mime', 'size_bytes', 'downloads_count', 'is_published'];

    protected $casts = [
        'size_bytes'      => 'integer',
        'downloads_count' => 'integer',
        'is_published'    => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
