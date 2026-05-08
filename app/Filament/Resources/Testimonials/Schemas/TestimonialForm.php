<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('role')->maxLength(255)->helperText('Mis. "Pemilik UMKM" / "Pengusaha".'),
                    Textarea::make('body')->required()->rows(4)->maxLength(1000)->columnSpanFull(),
                    Select::make('rating')->options([5 => '★★★★★', 4 => '★★★★', 3 => '★★★', 2 => '★★', 1 => '★']),
                    TextInput::make('sort_order')->numeric()->default(0),
                    FileUpload::make('photo_path')->label('Foto')->image()->directory('testimonials')->avatar()->maxSize(2048),
                    Toggle::make('is_published')->label('Publish?')->default(true),
                ]),
        ]);
    }
}
