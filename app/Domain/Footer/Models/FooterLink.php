<?php

namespace App\Domain\Footer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $fillable = ['group', 'label', 'url', 'open_in_new_tab', 'is_visible', 'sort_order'];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_visible'      => 'boolean',
        'sort_order'      => 'integer',
    ];

    public function scopeVisible(Builder $q): Builder
    {
        return $q->where('is_visible', true);
    }
}
