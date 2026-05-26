<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = ['type', 'name', 'slug', 'color', 'icon', 'sort_order'];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        // Jangan regenerasi slug saat update — jaga slug stabil & cegah drift
        // duplikat (mis. investasi → investasi-1, -2, … pada reseed berulang).
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }
}
