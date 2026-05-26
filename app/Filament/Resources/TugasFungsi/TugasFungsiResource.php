<?php

namespace App\Filament\Resources\TugasFungsi;

use App\Domain\Profil\Models\ProfilPoint;
use App\Filament\Resources\TugasFungsi\Pages\CreateTugasFungsi;
use App\Filament\Resources\TugasFungsi\Pages\EditTugasFungsi;
use App\Filament\Resources\TugasFungsi\Pages\ListTugasFungsi;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
use Illuminate\Database\Eloquent\Builder;

/**
 * One menu = one resource for the /profil/tugas-fungsi page. Manages the Tugas
 * Pokok (single row) and Fungsi (list) as rows of ProfilPoint limited to these
 * groups. The public view picks the single Tugas Pokok via firstWhere.
 */
class TugasFungsiResource extends Resource
{
    protected static ?string $model = ProfilPoint::class;

    protected static ?string $slug = 'tugas-fungsi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'Tugas & Fungsi';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Tugas / Fungsi';

    protected static ?string $pluralModelLabel = 'Tugas & Fungsi';

    private const GROUPS = [
        ProfilPoint::GROUP_TUGAS_POKOK => 'Tugas Pokok',
        ProfilPoint::GROUP_FUNGSI      => 'Fungsi',
    ];

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('group', array_keys(self::GROUPS));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten')->columns(2)->schema([
                Select::make('group')->label('Kelompok')->required()->live()
                    ->default(ProfilPoint::GROUP_FUNGSI)->options(self::GROUPS)
                    ->helperText('Tugas Pokok cukup satu entri; Fungsi bisa banyak.'),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                Textarea::make('body')->label('Isi')->required()->rows(3)->columnSpanFull(),
                Toggle::make('is_published')->label('Dipublikasikan')->default(true),
            ]),
            Section::make('Dasar Hukum & Dokumen')->columns(2)->collapsible()->schema([
                Select::make('regulations')->label('Dasar Hukum (Regulasi)')
                    ->relationship('regulations', 'title')->multiple()->preload()->searchable()
                    ->helperText('Mis. Peraturan Walikota tentang DPM-PTSP.'),
                Select::make('documents')->label('Dokumen')
                    ->relationship('documents', 'title')->multiple()->preload()->searchable(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->defaultGroup('group')
            ->columns([
                TextColumn::make('group')->label('Kelompok')->badge()
                    ->formatStateUsing(fn (string $state) => self::GROUPS[$state] ?? $state),
                TextColumn::make('body')->label('Isi')->limit(90)->wrap()->searchable(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
                IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->filters([
                SelectFilter::make('group')->label('Kelompok')->options(self::GROUPS),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTugasFungsi::route('/'),
            'create' => CreateTugasFungsi::route('/create'),
            'edit'   => EditTugasFungsi::route('/{record}/edit'),
        ];
    }
}
