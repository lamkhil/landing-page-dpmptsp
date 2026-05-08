<?php

namespace App\Domain\Statistic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StatisticCounter extends Model
{
    protected $fillable = ['key', 'label', 'value', 'unit', 'icon', 'color', 'is_visible', 'sort_order'];

    protected $casts = [
        'value'      => 'decimal:2',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeVisible(Builder $q): Builder
    {
        return $q->where('is_visible', true);
    }
}
