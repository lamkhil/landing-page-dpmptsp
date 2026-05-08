<?php

namespace App\Filament\Resources\FooterLinks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FooterLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    Select::make('group')
                        ->required()
                        ->options([
                            'quick'   => 'Tautan Cepat',
                            'service' => 'Layanan',
                            'partner' => 'Partner / Eksternal',
                            'external'=> 'Lainnya',
                        ]),
                    TextInput::make('sort_order')->numeric()->default(0),
                    TextInput::make('label')->required()->maxLength(255)->columnSpanFull(),
                    TextInput::make('url')->required()->maxLength(500)->columnSpanFull(),
                    Toggle::make('open_in_new_tab')->label('Buka di tab baru'),
                    Toggle::make('is_visible')->label('Tampilkan')->default(true),
                ]),
        ]);
    }
}
