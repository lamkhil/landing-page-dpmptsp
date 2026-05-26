<?php

namespace App\Filament\Resources\Regulations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegulationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Dokumen Regulasi')
                ->columns(2)
                ->schema([
                    TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                    Select::make('doc_type')->label('Jenis')->required()->default('perwali')->options([
                        'perwali' => 'Peraturan Walikota',
                        'perda'   => 'Peraturan Daerah',
                        'kepwali' => 'Keputusan Walikota',
                        'sk'      => 'Surat Keputusan',
                        'sop'     => 'SOP',
                    ]),
                    TextInput::make('doc_number')->label('Nomor')->maxLength(64)->placeholder('mis. 52'),
                    TextInput::make('doc_year')->label('Tahun')->numeric()->minValue(1990)->maxValue(2100)->required(),
                    DatePicker::make('signed_at')->label('Tanggal Ditetapkan'),
                    FileUpload::make('file_path')->label('Berkas (PDF)')->directory('regulations')
                        ->acceptedFileTypes(['application/pdf'])->maxSize(20480)->downloadable()->openable()->columnSpanFull(),
                    Toggle::make('is_published')->label('Dipublikasikan')->default(true),
                ]),
        ]);
    }
}
