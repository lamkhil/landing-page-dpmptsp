<?php

namespace App\Filament\Resources\News\Tables;

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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_path')->label('')->size(40),
                TextColumn::make('title')->label('Judul')->searchable()->weight('semibold')->limit(60)->wrap(),
                TextColumn::make('category.name')->label('Kategori')->badge()->placeholder('—')->toggleable(),
                TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    Post::STATUS_PUBLISHED => 'success',
                    Post::STATUS_DRAFT     => 'gray',
                    Post::STATUS_ARCHIVED  => 'warning',
                    default                => 'gray',
                }),
                IconColumn::make('is_featured')->boolean()->label('★')->toggleable(),
                TextColumn::make('view_count')->label('Views')->numeric()->sortable()->toggleable(),
                TextColumn::make('published_at')->label('Terbit')->dateTime('d M Y H:i')->placeholder('—')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    Post::STATUS_DRAFT => 'Draft', Post::STATUS_PUBLISHED => 'Published', Post::STATUS_ARCHIVED => 'Archived',
                ]),
                TernaryFilter::make('is_featured')->label('Headline')->trueLabel('Headline')->falseLabel('Biasa')->placeholder('Semua'),
                TrashedFilter::make(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([
                DeleteBulkAction::make(), ForceDeleteBulkAction::make(), RestoreBulkAction::make(),
            ])]);
    }
}
