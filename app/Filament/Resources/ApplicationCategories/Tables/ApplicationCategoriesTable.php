<?php

namespace App\Filament\Resources\ApplicationCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplicationCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('semibold'),
                TextColumn::make('slug')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Jumlah Aplikasi')
                    ->badge(),
                TextColumn::make('sort_order')->label('#')->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
