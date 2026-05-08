<?php

namespace App\Filament\Resources\StatisticCounters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class StatisticCountersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('label')->searchable()->weight('semibold'),
                TextColumn::make('key')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('value')->numeric()->sortable(),
                TextColumn::make('unit')->placeholder('—'),
                TextColumn::make('icon')->badge()->placeholder('—')->toggleable(),
                ToggleColumn::make('is_visible')->label('Tampil'),
                TextColumn::make('sort_order')->label('#'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->reorderable('sort_order');
    }
}
