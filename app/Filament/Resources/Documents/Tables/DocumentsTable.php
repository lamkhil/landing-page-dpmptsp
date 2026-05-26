<?php

namespace App\Filament\Resources\Documents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->weight('semibold')->limit(60)->wrap(),
                TextColumn::make('category.name')->label('Kategori')->badge()->placeholder('—')->toggleable(),
                IconColumn::make('is_published')->label('Publik')->boolean(),
                TextColumn::make('downloads_count')->label('Unduhan')->numeric()->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Dibuat')->date('d M Y')->sortable()->toggleable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
