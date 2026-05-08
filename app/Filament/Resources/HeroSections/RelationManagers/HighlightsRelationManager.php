<?php

namespace App\Filament\Resources\HeroSections\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HighlightsRelationManager extends RelationManager
{
    protected static string $relationship = 'highlights';

    protected static ?string $title = 'Highlight Cards';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('icon')->maxLength(64)->helperText('Heroicon name, mis. "shield-check".'),
            Textarea::make('description')->rows(2)->maxLength(500),
            TextInput::make('url')->maxLength(255),
            TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')->searchable()->weight('semibold'),
                TextColumn::make('icon')->badge(),
                TextColumn::make('sort_order')->label('#'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
