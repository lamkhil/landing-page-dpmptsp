<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('question')->searchable()->limit(80)->weight('semibold')->wrap(),
                TextColumn::make('category.name')->badge()->placeholder('—')->sortable(),
                ToggleColumn::make('is_published')->label('Publish'),
                TextColumn::make('view_count')->label('Views')->numeric()->sortable()->toggleable(),
                TextColumn::make('sort_order')->label('#')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->reorderable('sort_order');
    }
}
