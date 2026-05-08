<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regulation extends Model
{
    protected $fillable = ['post_id', 'title', 'doc_number', 'doc_year', 'doc_type', 'file_path', 'signed_at', 'is_published'];

    protected $casts = [
        'doc_year'     => 'integer',
        'signed_at'    => 'date',
        'is_published' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
