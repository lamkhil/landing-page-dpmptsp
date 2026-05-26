<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Infografis — proxy model atas tabel `posts` yang dikunci pada
 * type = "infografis".
 *
 * Sama seperti {@see News}, {@see Article}, {@see Announcement}: pemisahan
 * untuk Filament + Filament Shield agar Shield menghasilkan entity izin
 * "Infographic" yang terpisah. Semua perilaku diwarisi dari {@see Post}.
 */
class Infographic extends Post
{
    protected $table = 'posts';

    protected static function booted(): void
    {
        static::addGlobalScope('infographic', function (Builder $builder) {
            $builder->where($builder->getModel()->getTable().'.type', Post::TYPE_INFOGRAFIS);
        });

        static::creating(function (Infographic $infographic) {
            $infographic->type = Post::TYPE_INFOGRAFIS;
        });
    }
}
