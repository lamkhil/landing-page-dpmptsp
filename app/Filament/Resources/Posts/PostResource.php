<?php

namespace App\Filament\Resources\Posts;

use App\Domain\Content\Models\Post;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Informasi Publik';

    // Berita dipisah ke resource sendiri (NewsResource) untuk izin terpisah.
    // Resource ini menangani konten lain: Pengumuman, Artikel, Infografis, dll.
    protected static ?string $navigationLabel = 'Konten Lainnya';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Konten';

    protected static ?string $pluralModelLabel = 'Konten';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Berita, Pengumuman, Artikel & Infografis ditangani resource sendiri
        // — sembunyikan dari sini agar tidak dikelola ganda. Tersisa: Profil,
        // Zona Integritas, Inovasi.
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->whereNotIn('type', [
                Post::TYPE_NEWS, Post::TYPE_ANNOUNCEMENT, Post::TYPE_ARTICLE, Post::TYPE_INFOGRAFIS,
            ]);
    }
}
