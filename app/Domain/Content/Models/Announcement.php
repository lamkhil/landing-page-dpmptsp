<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Pengumuman — proxy model atas tabel `posts` yang dikunci pada
 * type = "announcement".
 *
 * Sama seperti {@see News} & {@see Article}: pemisahan untuk Filament +
 * Filament Shield agar Shield menghasilkan entity izin "Announcement"
 * yang terpisah. Semua relasi & perilaku diwarisi dari {@see Post}.
 */
class Announcement extends Post
{
    protected $table = 'posts';

    protected static function booted(): void
    {
        static::addGlobalScope('announcement', function (Builder $builder) {
            $builder->where($builder->getModel()->getTable().'.type', Post::TYPE_ANNOUNCEMENT);
        });

        static::creating(function (Announcement $announcement) {
            $announcement->type = Post::TYPE_ANNOUNCEMENT;
        });
    }
}
