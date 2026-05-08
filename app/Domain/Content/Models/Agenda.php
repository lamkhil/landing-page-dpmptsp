<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    protected $fillable = ['post_id', 'title', 'location', 'organizer', 'starts_at', 'ends_at', 'is_published'];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'is_published' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where('starts_at', '>=', now())->orderBy('starts_at');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
