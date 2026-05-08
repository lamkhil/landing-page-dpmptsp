<?php

namespace App\Domain\Statistic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatisticPeriod extends Model
{
    public const PERIOD_YEARLY    = 'yearly';
    public const PERIOD_QUARTERLY = 'quarterly';
    public const PERIOD_MONTHLY   = 'monthly';

    protected $fillable = ['statistic_group_id', 'period_type', 'year', 'month', 'quarter', 'value', 'label', 'notes'];

    protected $casts = [
        'year'    => 'integer',
        'month'   => 'integer',
        'quarter' => 'integer',
        'value'   => 'decimal:4',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(StatisticGroup::class, 'statistic_group_id');
    }

    public function scopeYearly(Builder $q): Builder
    {
        return $q->where('period_type', self::PERIOD_YEARLY);
    }
}
