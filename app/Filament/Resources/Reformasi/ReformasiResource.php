<?php

namespace App\Filament\Resources\Reformasi;

use App\Domain\Profil\Models\ProfilPoint;
use App\Domain\Profil\Models\ProfilPointDetail;
use App\Filament\Resources\Reformasi\Pages\CreateReformasi;
use App\Filament\Resources\Reformasi\Pages\EditReformasi;
use App\Filament\Resources\Reformasi\Pages\ListReformasi;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * One menu = one resource for /profil/reformasi-birokrasi. Manages the 6 ZI
 * area perubahan and the single Renja ZI link, as rows of ProfilPoint.
 */
class ReformasiResource extends Resource
{
    protected static ?string $model = ProfilPoint::class;

    protected static ?string $slug = 'reformasi-birokrasi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'Reformasi Birokrasi';

    protected static ?int $navigationSort = 9;

    protected static ?string $modelLabel = 'Area Perubahan / Renja ZI';

    protected static ?string $pluralModelLabel = 'Reformasi Birokrasi';

    private const GROUPS = [
        ProfilPoint::GROUP_AREA_RB  => 'Area Perubahan',
        ProfilPoint::GROUP_RENJA_ZI => 'Link Renja ZI',
        ProfilPoint::GROUP_SK_ZI    => 'SK ZI / Agen Perubahan',
        ProfilPoint::GROUP_WBK      => 'WBK (Media & Dokumentasi)',
        ProfilPoint::GROUP_WBBM     => 'Menuju WBBM (Media & Dokumentasi)',
    ];

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('group', array_keys(self::GROUPS));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten')->columns(2)->schema([
                Select::make('group')->label('Jenis')->required()->live()
                    ->default(ProfilPoint::GROUP_AREA_RB)->options(self::GROUPS)
                    ->helperText('"Area Perubahan" = salah satu dari 6 area; "Link Renja ZI" & "SK ZI / Agen Perubahan" masing-masing cukup satu entri.'),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                TextInput::make('title')->maxLength(255)->columnSpanFull()
                    ->label(fn (Get $get) => $get('group') === ProfilPoint::GROUP_SK_ZI ? 'Nomor / Judul SK ZI' : 'Nama Area')
                    ->visible(fn (Get $get) => in_array($get('group'), [ProfilPoint::GROUP_AREA_RB, ProfilPoint::GROUP_SK_ZI], true))
                    ->required(fn (Get $get) => $get('group') === ProfilPoint::GROUP_AREA_RB),
                Textarea::make('body')->rows(4)->columnSpanFull()->required()
                    ->label(fn (Get $get) => match ($get('group')) {
                        ProfilPoint::GROUP_RENJA_ZI                          => 'Keterangan Renja ZI',
                        ProfilPoint::GROUP_SK_ZI                             => 'URL Dokumen SK ZI',
                        ProfilPoint::GROUP_WBK, ProfilPoint::GROUP_WBBM      => 'Deskripsi Singkat',
                        default                                             => 'Deskripsi / Tujuan Area',
                    })
                    ->helperText(fn (Get $get) => match ($get('group')) {
                        ProfilPoint::GROUP_RENJA_ZI                          => 'Tombol "Buka Renja ZI" menautkan ke Dokumen internal yang dilampirkan pada "Dokumen / Media" di bawah.',
                        ProfilPoint::GROUP_SK_ZI                             => 'Tempel URL dokumen SK ZI (mis. Google Drive / PDF). Tampil sebagai rujukan di daftar Agen Perubahan.',
                        ProfilPoint::GROUP_WBK, ProfilPoint::GROUP_WBBM      => 'Lampirkan foto/dokumen pelaksanaan & penilaian pada "Dokumen / Media" di bawah — tampil sebagai galeri di halaman.',
                        default                                             => 'Menjadi paragraf "Deskripsi" pada modal area.',
                    }),
                Toggle::make('is_published')->label('Dipublikasikan')->default(true),
            ]),

            Section::make('Agen Perubahan')
                ->description(fn (Get $get) => $get('group') === ProfilPoint::GROUP_SK_ZI
                    ? 'Pimpinan Tim ZI (Ketua, Sekretaris) — tampil di section "Tim Pembangunan ZI" pada halaman.'
                    : 'Kelompok Kerja (Pokja) area ini: Koordinator + Anggota — tampil di modal area. Foto, nama, NIK/NIP, jabatan, peran.')
                ->visible(fn (Get $get) => in_array($get('group'), [ProfilPoint::GROUP_AREA_RB, ProfilPoint::GROUP_SK_ZI], true))
                ->schema([
                    Repeater::make('agents')
                        ->relationship()
                        ->hiddenLabel()
                        ->columns(2)
                        ->addActionLabel('Tambah agen perubahan')
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                        ->collapsible()
                        ->schema([
                            FileUpload::make('photo_path')->label('Foto')->image()->avatar()
                                ->directory('change-agents')->maxSize(2048)
                                ->helperText('Opsional. Kosong = pakai inisial nama.'),
                            TextInput::make('name')->label('Nama')->required()->maxLength(255),
                            TextInput::make('nip')->label('NIK / NIP')->maxLength(32),
                            TextInput::make('position')->label('Jabatan')->maxLength(255),
                            TextInput::make('role')->label('Peran')->maxLength(64)
                                ->datalist(['Pengarah', 'Ketua', 'Wakil Ketua', 'Sekretaris', 'Koordinator', 'Anggota'])
                                ->placeholder('mis. Ketua, Sekretaris, Anggota'),
                            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                            Toggle::make('is_published')->label('Publik')->default(true)->inline(false),
                        ]),
                ]),

            Section::make('Detail Area (Sasaran & Indikator)')
                ->description('Sasaran/Program dan Indikator Keberhasilan area ini, tampil sebagai daftar di modal.')
                ->visible(fn (Get $get) => $get('group') === ProfilPoint::GROUP_AREA_RB)
                ->schema([
                    Repeater::make('details')
                        ->relationship()
                        ->hiddenLabel()
                        ->columns(4)
                        ->addActionLabel('Tambah detail')
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => isset($state['kind']) ? (ProfilPointDetail::KINDS[$state['kind']] ?? null) : null)
                        ->schema([
                            Select::make('kind')->label('Jenis')->required()
                                ->options(ProfilPointDetail::KINDS)
                                ->default(ProfilPointDetail::KIND_SASARAN),
                            Textarea::make('body')->label('Isi')->required()->rows(2)->columnSpan(2),
                            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                            Toggle::make('is_published')->label('Publik')->default(true)->inline(false)->columnSpanFull(),
                        ]),
                ]),

            Section::make('Dokumen Terkait')->columns(2)->collapsible()->collapsed()->schema([
                Select::make('regulations')->label('Dasar Hukum (Regulasi)')
                    ->relationship('regulations', 'title')->multiple()->preload()->searchable(),
                Select::make('documents')->label('Dokumen / Media')
                    ->relationship('documents', 'title')->multiple()->preload()->searchable()
                    ->helperText('Untuk entri WBK / WBBM: lampirkan foto (tampil sebagai galeri) dan dokumen penilaian di sini.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->defaultGroup('group')
            ->columns([
                TextColumn::make('group')->label('Jenis')->badge()
                    ->formatStateUsing(fn (string $state) => self::GROUPS[$state] ?? $state),
                TextColumn::make('title')->label('Nama Area')->placeholder('—')->wrap(),
                TextColumn::make('body')->label('Isi')->limit(70)->wrap(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
                IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->filters([
                SelectFilter::make('group')->label('Jenis')->options(self::GROUPS),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReformasi::route('/'),
            'create' => CreateReformasi::route('/create'),
            'edit'   => EditReformasi::route('/{record}/edit'),
        ];
    }
}
