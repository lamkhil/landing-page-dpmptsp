<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Penempatan')
                ->columns(2)
                ->components([
                    Select::make('group')
                        ->label('Grup')
                        ->required()
                        ->options([
                            'beranda'   => 'Beranda',
                            'profil'    => 'Profil',
                            'layanan'   => 'Layanan',
                            'aplikasi'  => 'Aplikasi Publik',
                            'statistik' => 'Statistik',
                            'informasi' => 'Informasi Publik',
                            'dokumen'   => 'Dokumen Publik',
                            'pengaduan' => 'Pengaduan',
                            'kontak'    => 'Kontak',
                            'footer'    => 'Footer',
                        ])
                        ->helperText('Lokasi tampil menu — mengikuti grup navbar tetap.'),
                    Select::make('parent_id')
                        ->label('Parent (sub-menu)')
                        ->relationship('parent', 'label')
                        ->searchable()
                        ->preload(),
                    TextInput::make('label')->required()->maxLength(255)->columnSpanFull(),
                    TextInput::make('icon')->maxLength(64)->helperText('Nama heroicon. Opsional.'),
                    TextInput::make('sort_order')->numeric()->default(0),
                ]),

            Section::make('Tujuan')
                ->columns(1)
                ->description('Pilih SALAH SATU: rute internal (whitelist) atau URL eksternal.')
                ->components([
                    Select::make('route_name')
                        ->label('Nama Rute Internal')
                        ->options(config('dpmptsp.menu_routes'))
                        ->searchable()
                        ->helperText('Daftar rute terdaftar dari config(\'dpmptsp.menu_routes\'). Admin tidak dapat mengetik rute baru.'),
                    TextInput::make('external_url')
                        ->label('URL Eksternal')
                        ->url()
                        ->maxLength(500)
                        ->helperText('Untuk link ke domain di luar website ini.'),
                    Toggle::make('open_in_new_tab')->label('Buka di tab baru'),
                ]),

            Section::make('Visibilitas')
                ->components([
                    Toggle::make('is_visible')->label('Tampilkan di navbar')->default(true),
                ]),
        ]);
    }
}
