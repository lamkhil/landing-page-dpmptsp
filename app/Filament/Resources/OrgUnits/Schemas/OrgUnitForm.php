<?php

namespace App\Filament\Resources\OrgUnits\Schemas;

use App\Domain\Profil\Models\OrgUnit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrgUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Unit Organisasi')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('Nama Unit')->required()->maxLength(255)->columnSpanFull(),
                    Select::make('category')->label('Kategori')->required()->default(OrgUnit::CAT_BIDANG)
                        ->options(OrgUnit::CATEGORIES)
                        ->helperText('Pimpinan tampil sebagai kartu utama; Tim Kerja tampil di dalam unit induknya.'),
                    Select::make('parent_id')->label('Unit Induk')
                        ->relationship('parent', 'name', fn (Builder $query) => $query->where('category', '!=', OrgUnit::CAT_TIM_KERJA))
                        ->searchable()->preload()->placeholder('Tanpa induk (unit utama)')
                        ->helperText('Isi untuk menjadikannya tim kerja di bawah Bidang/Sekretariat.'),
                    Textarea::make('description')->label('Deskripsi / Tugas')->rows(3)->columnSpanFull(),
                    TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                    Toggle::make('is_published')->label('Dipublikasikan')->default(true),
                ]),

            Section::make('Dokumen Terkait')
                ->columns(2)
                ->collapsible()
                ->schema([
                    Select::make('regulations')->label('Dasar Hukum (Regulasi)')
                        ->relationship('regulations', 'title')->multiple()->preload()->searchable()
                        ->helperText('Mis. Peraturan Walikota tentang susunan organisasi.'),
                    Select::make('documents')->label('Dokumen')
                        ->relationship('documents', 'title')->multiple()->preload()->searchable(),
                ]),
        ]);
    }
}
