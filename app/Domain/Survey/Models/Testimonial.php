<?php

namespace App\Domain\Survey\Models;

use App\Support\MediaConversions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Testimonial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'role', 'body', 'rating', 'photo_path', 'is_published', 'sort_order'];

    protected $casts = [
        'rating'       => 'integer',
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        MediaConversions::register($this, $media);
    }
}
