<?php

namespace App\Domain\Faq\Models;

use App\Domain\Content\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    protected $fillable = ['category_id', 'question', 'body', 'is_published', 'sort_order', 'view_count'];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
        'view_count'   => 'integer',
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
