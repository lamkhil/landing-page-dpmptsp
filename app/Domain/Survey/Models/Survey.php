<?php

namespace App\Domain\Survey\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    protected $fillable = ['key', 'title', 'description', 'schema', 'is_active', 'opens_at', 'closes_at'];

    protected $casts = [
        'schema'    => 'array',
        'is_active' => 'boolean',
        'opens_at'  => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)
            ->where(fn ($qq) => $qq->whereNull('opens_at')->orWhere('opens_at', '<=', now()))
            ->where(fn ($qq) => $qq->whereNull('closes_at')->orWhere('closes_at', '>=', now()));
    }
}
