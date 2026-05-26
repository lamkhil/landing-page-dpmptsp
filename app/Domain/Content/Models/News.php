<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Berita — proxy model atas tabel `posts` yang dikunci pada type = "news".
 *
 * Tujuannya murni untuk pemisahan di Filament + Filament Shield: dengan model
 * tersendiri, Shield menghasilkan entity izin "News" (ViewAny:News, Create:News,
 * dst.) yang terpisah dari "Post". Sehingga role dapat diberi akses ke Berita
 * tanpa otomatis mendapat akses ke seluruh konten lain (Artikel, Pengumuman, …).
 *
 * Semua relasi, media collection, slug, dan scope diwarisi dari {@see Post}.
 */
class News extends Post
{
    protected $table = 'posts';

    protected static function booted(): void
    {
        // Selalu batasi query pada berita saja.
        static::addGlobalScope('news', function (Builder $builder) {
            $builder->where($builder->getModel()->getTable().'.type', Post::TYPE_NEWS);
        });

        // Paksa type saat membuat record baru lewat resource Berita.
        static::creating(function (News $news) {
            $news->type = Post::TYPE_NEWS;
        });
    }
}
