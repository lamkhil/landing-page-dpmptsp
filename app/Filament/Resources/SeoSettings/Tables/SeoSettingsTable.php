<?php

namespace App\Filament\Resources\SeoSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeoSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('page_key')
            ->columns([
                TextColumn::make('page_key')->badge()->searchable()->weight('semibold'),
                TextColumn::make('meta_title')->limit(50)->wrap(),
                TextColumn::make('robots')->badge(),
                TextColumn::make('updated_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
