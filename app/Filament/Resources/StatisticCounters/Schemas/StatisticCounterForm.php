<?php

namespace App\Filament\Resources\StatisticCounters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StatisticCounterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('key')->required()->unique(ignoreRecord: true)->maxLength(64)
                        ->helperText('Identifier internal, mis. "izin_diterbitkan".'),
                    TextInput::make('label')->required()->maxLength(255),
                    TextInput::make('value')->numeric()->required()->default(0),
                    TextInput::make('unit')->maxLength(32)->helperText('Mis. "izin", "USD juta", "skor".'),
                    TextInput::make('icon')->maxLength(64)->helperText('Heroicon name.'),
                    TextInput::make('color')->maxLength(16)->helperText('primary | accent | success | warning'),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_visible')->label('Tampilkan')->default(true),
                ]),
        ]);
    }
}
