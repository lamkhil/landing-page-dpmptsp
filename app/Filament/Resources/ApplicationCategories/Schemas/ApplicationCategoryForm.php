<?php

namespace App\Filament\Resources\ApplicationCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ApplicationCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state ?? ''))),
                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    TextInput::make('icon')
                        ->maxLength(64)
                        ->helperText('Nama heroicon, mis. "document-text".'),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                    Textarea::make('description')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
