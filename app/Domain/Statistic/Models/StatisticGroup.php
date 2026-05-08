<?php

namespace App\Domain\Statistic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatisticGroup extends Model
{
    protected $fillable = ['key', 'label', 'unit', 'description', 'sort_order'];

    public function periods(): HasMany
    {
        return $this->hasMany(StatisticPeriod::class);
    }
}
