<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\Domain\Application\Models\Application;
use App\Domain\Application\Models\ApplicationCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identitas')
                ->columns(2)
                ->components([
                    TextInput::make('name')
                        ->label('Nama Aplikasi')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state ?? ''))),
                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('URL-friendly identifier. Auto-generated dari nama.'),
                    Select::make('application_category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')->required()->maxLength(255),
                            TextInput::make('slug')->required()->unique(ApplicationCategory::class, 'slug'),
                        ]),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Urutan tampil pada daftar publik (kecil = atas).'),
                    Textarea::make('description')
                        ->maxLength(500)
                        ->columnSpanFull()
                        ->rows(3),
                ]),

            Section::make('Tautan')
                ->columns(2)
                ->components([
                    Select::make('link_type')
                        ->label('Tipe Tautan')
                        ->options([
                            Application::LINK_EXTERNAL => 'External (URL)',
                            Application::LINK_INTERNAL => 'Internal (rute)',
                            Application::LINK_API      => 'API integrasi',
                        ])
                        ->required()
                        ->default(Application::LINK_EXTERNAL),
                    TextInput::make('url')
                        ->label('URL / Rute')
                        ->required()
                        ->helperText('Untuk Internal isi path seperti /layanan/tracking. Untuk External isi URL lengkap.'),
                ]),

            Section::make('Tampilan')
                ->columns(2)
                ->components([
                    FileUpload::make('icon_path')
                        ->label('Ikon')
                        ->image()
                        ->directory('applications/icons')
                        ->maxSize(512)
                        ->imageEditor(),
                    FileUpload::make('thumbnail_path')
                        ->label('Thumbnail')
                        ->image()
                        ->directory('applications/thumbnails')
                        ->maxSize(2048)
                        ->imageEditor(),
                ]),

            Section::make('Status')
                ->columns(3)
                ->components([
                    Select::make('status')
                        ->required()
                        ->options([
                            Application::STATUS_ACTIVE      => 'Aktif',
                            Application::STATUS_INACTIVE    => 'Tidak Aktif',
                            Application::STATUS_MAINTENANCE => 'Maintenance',
                        ])
                        ->default(Application::STATUS_ACTIVE),
                    Toggle::make('is_featured')
                        ->label('Featured?')
                        ->helperText('Muncul di section "Aplikasi Publik" pada halaman beranda.'),
                    DateTimePicker::make('published_at')
                        ->label('Publish at')
                        ->default(now())
                        ->helperText('Kosongkan untuk menyimpan sebagai draft.'),
                ]),
        ]);
    }
}
