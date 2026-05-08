<?php

namespace App\Filament\Resources\HeroSections\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten')
                ->columns(2)
                ->components([
                    TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                    TextInput::make('subtitle')->label('Subjudul')->maxLength(255),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Textarea::make('description')->label('Deskripsi')->rows(3)->maxLength(1000)->columnSpanFull(),
                    Textarea::make('running_text')->label('Running text')->rows(2)->maxLength(500)->columnSpanFull(),
                ]),

            Section::make('Media')
                ->columns(2)
                ->components([
                    FileUpload::make('background_path')->label('Background')->image()->directory('hero')->maxSize(4096)->imageEditor(),
                    FileUpload::make('video_path')->label('Video (mp4)')->acceptedFileTypes(['video/mp4'])->directory('hero')->maxSize(20480),
                ]),

            Section::make('Call to Action')
                ->columns(2)
                ->components([
                    TextInput::make('cta_label')->label('Label utama')->maxLength(64),
                    TextInput::make('cta_url')->label('URL utama')->maxLength(255),
                    TextInput::make('secondary_cta_label')->label('Label sekunder')->maxLength(64),
                    TextInput::make('secondary_cta_url')->label('URL sekunder')->maxLength(255),
                ]),

            Section::make('Publikasi')
                ->columns(2)
                ->components([
                    Toggle::make('is_active')->label('Aktif?')->default(true)->helperText('Hanya satu hero aktif yang ditampilkan pada beranda.'),
                    DateTimePicker::make('published_at')->label('Publish at')->default(now()),
                ]),
        ]);
    }
}
