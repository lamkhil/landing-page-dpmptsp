<?php

namespace App\Filament\Resources\HeroSections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HeroSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')->searchable()->weight('semibold'),
                TextColumn::make('subtitle')->limit(40)->toggleable(),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('published_at')->dateTime('d M Y H:i')->placeholder('— draft —')->sortable(),
                TextColumn::make('sort_order')->label('#')->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
