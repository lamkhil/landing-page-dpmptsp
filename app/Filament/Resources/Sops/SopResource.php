<?php

namespace App\Filament\Resources\Sops;

use App\Domain\Profil\Models\Sop;
use App\Filament\Resources\Sops\Pages\CreateSop;
use App\Filament\Resources\Sops\Pages\EditSop;
use App\Filament\Resources\Sops\Pages\ListSops;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SopResource extends Resource
{
    protected static ?string $model = Sop::class;

    protected static ?string $slug = 'sop';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'SOP Pelayanan';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'SOP';

    protected static ?string $pluralModelLabel = 'SOP Pelayanan';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Dokumen SOP')->columns(2)->schema([
                TextInput::make('title')->label('Judul SOP')->required()->maxLength(255)->columnSpanFull(),
                // Selectable category that can also be typed/created on the fly.
                Select::make('sop_category_id')->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()->preload()
                    ->placeholder('Pilih atau ketik kategori baru')
                    ->createOptionForm([
                        TextInput::make('name')->label('Nama Kategori')->required()->maxLength(255),
                        TextInput::make('description')->label('Deskripsi')->maxLength(255),
                        TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                    ])
                    ->createOptionModalHeading('Tambah Kategori SOP')
                    ->helperText('Ketik untuk mencari; jika belum ada, gunakan "Buat kategori" untuk menambah.'),
                TextInput::make('doc_number')->label('Nomor SOP')->maxLength(100)->placeholder('opsional'),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                Toggle::make('is_published')->label('Dipublikasikan')->default(true),
            ]),

            Section::make('Dokumen per Tahun')
                ->description('Tambah versi dokumen per tahun (2024/2025/2026…). Tahun yang muncul di halaman publik mengikuti yang diunggah di sini.')
                ->schema([
                    Repeater::make('files')
                        ->relationship()
                        ->hiddenLabel()
                        ->columns(3)
                        ->addActionLabel('Tambah tahun')
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->schema([
                            Select::make('year')->label('Tahun')->required()
                                ->options(collect(range((int) date('Y') + 1, 2018))->mapWithKeys(fn ($y) => [$y => $y])->all()),
                            FileUpload::make('file_path')->label('Berkas (PDF)')->directory('sop')
                                ->acceptedFileTypes(['application/pdf'])->maxSize(20480)->downloadable()->openable()
                                ->helperText('Kosongkan dulu jika belum tersedia.'),
                            Toggle::make('is_published')->label('Publik')->default(true)->inline(false),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')->label('Judul SOP')->searchable()->weight('semibold')->wrap(),
                TextColumn::make('category.name')->label('Kategori')->badge()->placeholder('—')->sortable(),
                TextColumn::make('doc_number')->label('Nomor')->placeholder('—')->toggleable(),
                TextColumn::make('files_count')->counts('files')->label('Versi Tahun')->badge()->placeholder('0'),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
                IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->filters([
                SelectFilter::make('sop_category_id')->label('Kategori')->relationship('category', 'name'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSops::route('/'),
            'create' => CreateSop::route('/create'),
            'edit'   => EditSop::route('/{record}/edit'),
        ];
    }
}
