<?php

namespace App\Filament\Resources\FooterLinks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FooterLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->columns([
                TextColumn::make('label')->searchable()->weight('semibold'),
                TextColumn::make('group')->badge(),
                TextColumn::make('url')->limit(50)->copyable(),
                ToggleColumn::make('is_visible')->label('Tampil'),
                TextColumn::make('sort_order')->label('#')->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')->options([
                    'quick' => 'Tautan Cepat', 'service' => 'Layanan',
                    'partner' => 'Partner', 'external' => 'Lainnya',
                ]),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->reorderable('sort_order');
    }
}
