<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->searchable()->weight('semibold'),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('roles.name')->label('Role')->badge()->color('primary'),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('last_login_at')->dateTime('d M Y H:i')->placeholder('—')->sortable()->toggleable(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
