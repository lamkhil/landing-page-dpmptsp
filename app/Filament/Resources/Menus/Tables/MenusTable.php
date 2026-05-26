<?php

namespace App\Filament\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->columns([
                TextColumn::make('label')->searchable()->weight('semibold'),
                TextColumn::make('group')->badge(),
                TextColumn::make('parent.label')->label('Parent')->placeholder('—'),
                TextColumn::make('route_name')->toggleable(),
                TextColumn::make('external_url')->limit(40)->toggleable(),
                ToggleColumn::make('is_visible')->label('Tampil'),
                TextColumn::make('sort_order')->label('#')->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')->options([
                    'beranda' => 'Beranda', 'profil' => 'Profil', 'layanan' => 'Layanan',
                    'aplikasi' => 'Aplikasi Publik', 'statistik' => 'Statistik',
                    'informasi' => 'Informasi Publik', 'dokumen' => 'Dokumen Publik',
                    'pengaduan' => 'Pengaduan', 'kontak' => 'Kontak', 'footer' => 'Footer',
                ]),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->reorderable('sort_order');
    }
}
