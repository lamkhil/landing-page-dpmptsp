<?php

namespace App\Domain\Application\Models;

use App\Support\MediaConversions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Application extends Model implements HasMedia
{
    use HasSlug;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    public const STATUS_ACTIVE      = 'active';
    public const STATUS_INACTIVE    = 'inactive';
    public const STATUS_MAINTENANCE = 'maintenance';

    public const LINK_EXTERNAL = 'external';
    public const LINK_INTERNAL = 'internal';
    public const LINK_API      = 'api';

    protected $fillable = [
        'application_category_id', 'name', 'slug', 'description', 'icon_path',
        'thumbnail_path', 'url', 'link_type', 'status', 'is_featured',
        'sort_order', 'published_at',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'sort_order'   => 'integer',
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'is_featured', 'url', 'application_category_id', 'published_at'])
            ->logOnlyDirty();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ApplicationCategory::class, 'application_category_id');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_ACTIVE);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')->singleFile();
        $this->addMediaCollection('thumbnail')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        MediaConversions::register($this, $media);
    }
}
