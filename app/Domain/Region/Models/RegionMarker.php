<?php

namespace App\Domain\Region\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionMarker extends Model
{
    protected $fillable = ['region_id', 'name', 'category', 'latitude', 'longitude', 'popup_html', 'icon', 'is_visible'];

    protected $casts = [
        'latitude'   => 'float',
        'longitude'  => 'float',
        'is_visible' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function scopeVisible(Builder $q): Builder
    {
        return $q->where('is_visible', true);
    }
}
