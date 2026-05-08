<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('photo_path')->circular()->size(40),
                TextColumn::make('name')->searchable()->weight('semibold'),
                TextColumn::make('role')->placeholder('—')->toggleable(),
                TextColumn::make('rating')->formatStateUsing(fn (?int $state) => $state ? str_repeat('★', $state) : '—')->color('warning'),
                ToggleColumn::make('is_published')->label('Publish'),
                TextColumn::make('sort_order')->label('#'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
