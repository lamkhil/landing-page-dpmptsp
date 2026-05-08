<?php

namespace App\Domain\Hero\Models;

use App\Support\MediaConversions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HeroSection extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'title', 'subtitle', 'description', 'background_path', 'video_path',
        'cta_label', 'cta_url', 'secondary_cta_label', 'secondary_cta_url',
        'running_text', 'is_active', 'sort_order', 'published_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
        'published_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'is_active', 'published_at', 'sort_order'])
            ->logOnlyDirty();
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(HeroHighlight::class)->orderBy('sort_order');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('background')->singleFile();
        $this->addMediaCollection('video')
            ->singleFile()
            ->acceptsMimeTypes(['video/mp4', 'video/webm']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Skip conversions on video collection (no thumbnails for video files via this preset).
        if ($media && $media->collection_name === 'video') {
            return;
        }
        MediaConversions::register($this, $media);
    }
}
