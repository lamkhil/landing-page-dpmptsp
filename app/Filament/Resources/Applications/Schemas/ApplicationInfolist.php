<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    ImageEntry::make('icon_path')->label('Ikon')->circular(),
                    TextEntry::make('name')->label('Nama')->weight('semibold'),
                    TextEntry::make('category.name')->label('Kategori')->badge(),
                    TextEntry::make('status')->badge(),
                    IconEntry::make('is_featured')->boolean()->label('Featured'),
                    TextEntry::make('url')->copyable(),
                    TextEntry::make('description')->columnSpanFull()->prose(),
                    TextEntry::make('published_at')->dateTime('d M Y H:i'),
                    TextEntry::make('updated_at')->dateTime('d M Y H:i'),
                ]),
        ]);
    }
}
