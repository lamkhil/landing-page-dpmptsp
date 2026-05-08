<?php

namespace App\Domain\Content\Models;

use App\Models\User;
use App\Support\MediaConversions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasSlug;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    public const TYPE_NEWS         = 'news';
    public const TYPE_ANNOUNCEMENT = 'announcement';
    public const TYPE_ARTICLE      = 'article';
    public const TYPE_INFOGRAFIS   = 'infografis';
    public const TYPE_PROFIL       = 'profil';
    public const TYPE_ZI           = 'zi-content';

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED  = 'archived';

    protected $fillable = [
        'type', 'category_id', 'author_id', 'title', 'slug', 'excerpt', 'body',
        'cover_path', 'status', 'is_featured', 'view_count', 'published_at',
        'scheduled_at', 'meta_title', 'meta_description', 'og_image_path',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'view_count'   => 'integer',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'published_at', 'is_featured', 'category_id'])
            ->logOnlyDirty();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('og_image')->singleFile();
        $this->addMediaCollection('inline');   // attachments inside RichEditor
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        MediaConversions::register($this, $media);
    }
}
