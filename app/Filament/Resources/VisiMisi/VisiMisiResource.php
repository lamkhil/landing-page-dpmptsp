<?php

namespace App\Filament\Resources\VisiMisi;

use App\Domain\Profil\Models\ProfilPoint;
use App\Filament\Resources\VisiMisi\Pages\CreateVisiMisi;
use App\Filament\Resources\VisiMisi\Pages\EditVisiMisi;
use App\Filament\Resources\VisiMisi\Pages\ListVisiMisi;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * One menu = one resource for the /profil/visi-misi page. Manages the Visi
 * (single row), Misi, and Fokus Strategis (lists) — all rows of ProfilPoint
 * limited to these groups. The public view picks the single Visi via firstWhere.
 */
class VisiMisiResource extends Resource
{
    protected static ?string $model = ProfilPoint::class;

    protected static ?string $slug = 'visi-misi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'Visi & Misi';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Visi / Misi / Fokus';

    protected static ?string $pluralModelLabel = 'Visi & Misi';

    private const GROUPS = [
        ProfilPoint::GROUP_VISI  => 'Visi',
        ProfilPoint::GROUP_MISI  => 'Misi',
        ProfilPoint::GROUP_FOKUS => 'Fokus Strategis',
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
                    ->default(ProfilPoint::GROUP_MISI)->options(self::GROUPS)
                    ->helperText('Visi cukup satu entri; Misi dan Fokus Strategis bisa banyak.'),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                TextInput::make('title')->label('Judul Pilar')->maxLength(255)->columnSpanFull()
                    ->helperText('Khusus Fokus Strategis (judul pilar).')
                    ->visible(fn (Get $get) => $get('group') === ProfilPoint::GROUP_FOKUS)
                    ->required(fn (Get $get) => $get('group') === ProfilPoint::GROUP_FOKUS),
                Textarea::make('body')->label('Isi')->required()->rows(3)->columnSpanFull(),
                Toggle::make('is_published')->label('Dipublikasikan')->default(true),
            ]),
            Section::make('Dokumen Terkait')->columns(2)->collapsible()->schema([
                Select::make('regulations')->label('Dasar Hukum (Regulasi)')
                    ->relationship('regulations', 'title')->multiple()->preload()->searchable(),
                Select::make('documents')->label('Dokumen')
                    ->relationship('documents', 'title')->multiple()->preload()->searchable()
                    ->helperText('Mis. Renstra DPMPTSP, RPJMD Kota Surabaya.'),
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
                TextColumn::make('title')->label('Judul')->placeholder('—')->toggleable(),
                TextColumn::make('body')->label('Isi')->limit(80)->wrap()->searchable(),
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
            'index'  => ListVisiMisi::route('/'),
            'create' => CreateVisiMisi::route('/create'),
            'edit'   => EditVisiMisi::route('/{record}/edit'),
        ];
    }
}
