<?php

namespace App\Domain\Menu\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Menu extends Model
{
    use LogsActivity;

    protected $fillable = [
        'parent_id', 'group', 'label', 'route_name', 'external_url',
        'icon', 'is_visible', 'open_in_new_tab', 'sort_order',
    ];

    protected $casts = [
        'is_visible'      => 'boolean',
        'open_in_new_tab' => 'boolean',
        'sort_order'      => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['label', 'route_name', 'external_url', 'is_visible', 'sort_order', 'parent_id'])
            ->logOnlyDirty();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeVisible(Builder $q): Builder
    {
        return $q->where('is_visible', true);
    }

    public function scopeInGroup(Builder $q, string $group): Builder
    {
        return $q->where('group', $group);
    }

    public function resolveUrl(): string
    {
        if ($this->external_url) {
            return $this->external_url;
        }
        if ($this->route_name && \Illuminate\Support\Facades\Route::has($this->route_name)) {
            return route($this->route_name);
        }
        return '#';
    }
}
