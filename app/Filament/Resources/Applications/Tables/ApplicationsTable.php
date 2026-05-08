<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Domain\Application\Models\Application;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('icon_path')
                    ->label('Ikon')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        Application::STATUS_ACTIVE      => 'success',
                        Application::STATUS_MAINTENANCE => 'warning',
                        default                         => 'gray',
                    }),
                ToggleColumn::make('is_featured')->label('Featured'),
                TextColumn::make('link_type')
                    ->label('Tipe')
                    ->badge()
                    ->color('info'),
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('published_at')
                    ->dateTime('d M Y H:i')
                    ->placeholder('— draft —')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('application_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->options([
                        Application::STATUS_ACTIVE      => 'Aktif',
                        Application::STATUS_INACTIVE    => 'Tidak Aktif',
                        Application::STATUS_MAINTENANCE => 'Maintenance',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
