<?php

namespace App\Domain\Hero\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroHighlight extends Model
{
    protected $fillable = ['hero_section_id', 'title', 'description', 'icon', 'url', 'sort_order'];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function heroSection(): BelongsTo
    {
        return $this->belongsTo(HeroSection::class);
    }
}
