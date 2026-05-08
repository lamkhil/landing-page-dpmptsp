<?php

namespace App\Domain\Region\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = ['name', 'kode_wilayah', 'geojson_path', 'description', 'sort_order'];

    public function markers(): HasMany
    {
        return $this->hasMany(RegionMarker::class);
    }
}
