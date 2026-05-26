<?php

namespace App\Filament\Resources\Maklumat;

use App\Domain\Profil\Models\ProfilPoint;
use App\Filament\Resources\Maklumat\Pages\CreateMaklumat;
use App\Filament\Resources\Maklumat\Pages\EditMaklumat;
use App\Filament\Resources\Maklumat\Pages\ListMaklumat;
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
 * One menu = one resource for the /profil/maklumat-pelayanan page. Manages the
 * Naskah Maklumat (single pledge statement) and Komitmen Pelayanan (list) as
 * rows of ProfilPoint limited to these groups. The official naskah image is the
 * Post cover (edit via Konten → Maklumat Pelayanan).
 */
class MaklumatResource extends Resource
{
    protected static ?string $model = ProfilPoint::class;

    protected static ?string $slug = 'maklumat-pelayanan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'Maklumat Pelayanan';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Maklumat / Komitmen';

    protected static ?string $pluralModelLabel = 'Maklumat Pelayanan';

    private const GROUPS = [
        ProfilPoint::GROUP_MAKLUMAT => 'Naskah Maklumat',
        ProfilPoint::GROUP_KOMITMEN => 'Komitmen Pelayanan',
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
                    ->default(ProfilPoint::GROUP_KOMITMEN)->options(self::GROUPS)
                    ->helperText('Naskah Maklumat cukup satu entri; Komitmen Pelayanan bisa banyak.'),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                Textarea::make('body')->label('Isi')->required()->rows(3)->columnSpanFull(),
                Toggle::make('is_published')->label('Dipublikasikan')->default(true),
            ]),
            Section::make('Dasar Hukum & Dokumen')->columns(2)->collapsible()->schema([
                Select::make('regulations')->label('Dasar Hukum (Regulasi)')
                    ->relationship('regulations', 'title')->multiple()->preload()->searchable()
                    ->helperText('Mis. UU 25/2009 tentang Pelayanan Publik (jika tersedia).'),
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
            'index'  => ListMaklumat::route('/'),
            'create' => CreateMaklumat::route('/create'),
            'edit'   => EditMaklumat::route('/{record}/edit'),
        ];
    }
}
