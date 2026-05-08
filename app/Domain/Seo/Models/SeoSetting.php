<?php

namespace App\Domain\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SeoSetting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'page_key', 'meta_title', 'meta_description', 'keywords',
        'og_image_path', 'structured_data', 'robots', 'canonical_url',
    ];

    protected $casts = [
        'structured_data' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
