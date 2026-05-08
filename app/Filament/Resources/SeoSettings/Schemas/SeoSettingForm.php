<?php

namespace App\Filament\Resources\SeoSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SeoSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman')
                ->columns(2)
                ->components([
                    Select::make('page_key')
                        ->required()
                        ->options([
                            'home' => 'Beranda',
                            'profil' => 'Profil',
                            'layanan' => 'Layanan',
                            'aplikasi' => 'Aplikasi Publik',
                            'statistik' => 'Statistik',
                            'informasi' => 'Informasi Publik',
                            'pengaduan' => 'Pengaduan',
                            'kontak' => 'Kontak',
                        ])
                        ->unique(ignoreRecord: true),
                    Select::make('robots')
                        ->required()
                        ->default('index,follow')
                        ->options([
                            'index,follow'     => 'Index, Follow',
                            'index,nofollow'   => 'Index, NoFollow',
                            'noindex,follow'   => 'NoIndex, Follow',
                            'noindex,nofollow' => 'NoIndex, NoFollow',
                        ]),
                ]),

            Section::make('Meta')
                ->components([
                    TextInput::make('meta_title')->maxLength(255),
                    Textarea::make('meta_description')->rows(3)->maxLength(500),
                    TextInput::make('keywords')->maxLength(255),
                    TextInput::make('canonical_url')->url()->maxLength(255),
                    FileUpload::make('og_image_path')->label('OG Image')->image()->directory('seo/og')->maxSize(2048),
                ]),

            Section::make('JSON-LD (structured data)')
                ->collapsible()
                ->collapsed()
                ->components([
                    KeyValue::make('structured_data')
                        ->keyLabel('Field')
                        ->valueLabel('Value')
                        ->reorderable()
                        ->helperText('Schema.org JSON-LD untuk halaman ini. Contoh: @type → GovernmentOrganization, name → DPMPTSP Surabaya.'),
                ]),
        ]);
    }
}
