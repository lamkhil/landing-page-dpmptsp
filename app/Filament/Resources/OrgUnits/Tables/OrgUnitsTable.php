<?php

namespace App\Filament\Resources\OrgUnits\Tables;

use App\Domain\Profil\Models\OrgUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrgUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->label('Nama Unit')->searchable()->weight('semibold')->wrap(),
                TextColumn::make('category')->label('Kategori')->badge()
                    ->formatStateUsing(fn (string $state) => OrgUnit::CATEGORIES[$state] ?? $state)->sortable(),
                TextColumn::make('parent.name')->label('Induk')->placeholder('—')->toggleable(),
                TextColumn::make('regulations.title')->label('Dasar Hukum')->badge()->limit(20)->placeholder('—')->toggleable(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
                IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')->label('Kategori')->options(OrgUnit::CATEGORIES),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
