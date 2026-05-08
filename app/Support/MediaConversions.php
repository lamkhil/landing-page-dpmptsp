<?php

namespace App\Support;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Centralized image conversion presets used by all CMS models that hold images.
 * Three responsive sizes, all WebP, queued. Manifest:
 *
 *  - thumb  → 320w  (avatars, list rows, small cards)
 *  - card   → 640w  (grid cards, featured tiles)
 *  - banner → 1600w (hero/cover, OG images)
 *
 * Models implement HasMedia + InteractsWithMedia and call:
 *   MediaConversions::register($this, $media);
 * inside their registerMediaConversions() override.
 */
class MediaConversions
{
    public static function register(HasMedia $model, ?Media $media = null): void
    {
        $model->addMediaConversion('thumb')
            ->fit(Fit::Crop, 320, 320)
            ->format('webp')
            ->quality(82)
            ->sharpen(8)
            ->nonQueued()           // small + needed immediately for previews
            ->keepOriginalImageFormat();

        $model->addMediaConversion('card')
            ->fit(Fit::Contain, 640, 640)
            ->format('webp')
            ->quality(82)
            ->queued();

        $model->addMediaConversion('banner')
            ->fit(Fit::Contain, 1600, 900)
            ->format('webp')
            ->quality(80)
            ->queued();
    }
}
