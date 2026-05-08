<?php

namespace App\Domain\Footer\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FooterSetting extends Model
{
    use LogsActivity;

    protected $fillable = ['address', 'phone', 'email', 'office_hours', 'embed_map_url', 'social_links', 'about_text'];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public static function singleton(): self
    {
        $row = self::query()->oldest('id')->first();
        if ($row) {
            return $row;
        }
        // Create without mass-assigning `id` (Model::shouldBeStrict() rejects it).
        $row = new self();
        $row->save();
        return $row;
    }
}
