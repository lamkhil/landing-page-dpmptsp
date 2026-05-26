<?php

namespace App\Domain\Content\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Artikel — proxy model atas tabel `posts` yang dikunci pada type = "article".
 *
 * Sama seperti {@see News}: tujuannya pemisahan di Filament + Filament Shield
 * agar Shield menghasilkan entity izin "Article" (ViewAny:Article, dst.) yang
 * terpisah dari "Post"/"News". Semua relasi & perilaku diwarisi dari {@see Post}.
 */
class Article extends Post
{
    protected $table = 'posts';

    protected static function booted(): void
    {
        static::addGlobalScope('article', function (Builder $builder) {
            $builder->where($builder->getModel()->getTable().'.type', Post::TYPE_ARTICLE);
        });

        static::creating(function (Article $article) {
            $article->type = Post::TYPE_ARTICLE;
        });
    }
}
