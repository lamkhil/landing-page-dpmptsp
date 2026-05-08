<?php

namespace App\Domain\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ApplicationCategory extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order'];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
