<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Domain\Content\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_path')->label('')->size(40),
                TextColumn::make('title')->searchable()->weight('semibold')->limit(60)->wrap(),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('category.name')->badge()->placeholder('—')->toggleable(),
                TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    Post::STATUS_PUBLISHED => 'success',
                    Post::STATUS_DRAFT     => 'gray',
                    Post::STATUS_ARCHIVED  => 'warning',
                    default                => 'gray',
                }),
                IconColumn::make('is_featured')->boolean()->label('★')->toggleable(),
                TextColumn::make('view_count')->label('Views')->numeric()->sortable()->toggleable(),
                TextColumn::make('published_at')->dateTime('d M Y H:i')->placeholder('—')->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')->options([
                    Post::TYPE_PROFIL       => 'Profil',
                    Post::TYPE_ZI           => 'Zona Integritas',
                ]),
                SelectFilter::make('status')->options([
                    Post::STATUS_DRAFT => 'Draft', Post::STATUS_PUBLISHED => 'Published', Post::STATUS_ARCHIVED => 'Archived',
                ]),
                TrashedFilter::make(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([
                DeleteBulkAction::make(), ForceDeleteBulkAction::make(), RestoreBulkAction::make(),
            ])]);
    }
}
