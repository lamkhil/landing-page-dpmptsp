<?php

namespace App\Filament\Resources\ServiceStandardDocuments;

use App\Domain\Profil\Models\ServiceStandardDocument;
use App\Filament\Resources\ServiceStandardDocuments\Pages\CreateServiceStandardDocument;
use App\Filament\Resources\ServiceStandardDocuments\Pages\EditServiceStandardDocument;
use App\Filament\Resources\ServiceStandardDocuments\Pages\ListServiceStandardDocuments;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceStandardDocumentResource extends Resource
{
    protected static ?string $model = ServiceStandardDocument::class;

    protected static ?string $slug = 'standar-dokumen';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'Dokumen Standar (Tahunan)';

    protected static ?int $navigationSort = 8;

    protected static ?string $modelLabel = 'Dokumen Standar';

    protected static ?string $pluralModelLabel = 'Dokumen Standar (Tahunan)';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Dokumen Standar Pelayanan (per Tahun)')->columns(2)->schema([
                Select::make('year')->label('Tahun')->required()
                    ->options(collect(range((int) date('Y') + 1, 2018))->mapWithKeys(fn ($y) => [$y => $y])->all()),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                TextInput::make('title')->label('Judul')->maxLength(255)->columnSpanFull()
                    ->placeholder('mis. Standar Pelayanan DPMPTSP Tahun 2025'),
                FileUpload::make('file_path')->label('Berkas (PDF)')->directory('standar-pelayanan')
                    ->acceptedFileTypes(['application/pdf'])->maxSize(20480)->downloadable()->openable()->columnSpanFull()
                    ->helperText('Dokumen resmi yang mencakup seluruh layanan untuk tahun tersebut.'),
                Toggle::make('is_published')->label('Dipublikasikan')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('year', 'desc')
            ->columns([
                TextColumn::make('year')->label('Tahun')->weight('semibold')->sortable(),
                TextColumn::make('title')->label('Judul')->placeholder('—')->wrap(),
                IconColumn::make('file_path')->label('Berkas')->boolean()
                    ->trueIcon('heroicon-o-document-arrow-down')->falseIcon('heroicon-o-clock'),
                IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListServiceStandardDocuments::route('/'),
            'create' => CreateServiceStandardDocument::route('/create'),
            'edit'   => EditServiceStandardDocument::route('/{record}/edit'),
        ];
    }
}
