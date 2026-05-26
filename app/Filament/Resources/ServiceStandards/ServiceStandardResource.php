<?php

namespace App\Filament\Resources\ServiceStandards;

use App\Domain\Profil\Models\ServiceStandard;
use App\Filament\Resources\ServiceStandards\Pages\CreateServiceStandard;
use App\Filament\Resources\ServiceStandards\Pages\EditServiceStandard;
use App\Filament\Resources\ServiceStandards\Pages\ListServiceStandards;
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

class ServiceStandardResource extends Resource
{
    protected static ?string $model = ServiceStandard::class;

    protected static ?string $slug = 'standar-pelayanan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'Standar Pelayanan';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'Layanan';

    protected static ?string $pluralModelLabel = 'Standar Pelayanan';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Layanan')->columns(2)->schema([
                TextInput::make('name')->label('Nama Layanan / Kategori')->required()->maxLength(255)->columnSpanFull(),
                Select::make('parent_id')->label('Induk (Kategori)')
                    ->relationship('parent', 'name')
                    ->searchable()->preload()
                    ->placeholder('— Tanpa induk (kategori utama) —')
                    ->helperText('Kosongkan untuk kategori utama. Isi untuk menjadikannya sub-kategori / layanan di dalamnya (hierarki bertingkat).'),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                Toggle::make('is_published')->label('Dipublikasikan')->default(true),
            ]),

            Section::make('Detail Layanan')
                ->description('Diisi untuk layanan (item paling bawah). Kategori induk boleh dikosongkan. Bagian yang kosong tidak ditampilkan di halaman publik.')
                ->columns(1)
                ->collapsible()
                ->schema(
                    collect(ServiceStandard::COMPONENTS)
                        ->map(fn (string $label, string $key) => Textarea::make($key)->label($label)->rows(3))
                        ->values()
                        ->all()
                ),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->label('Nama Layanan')->searchable()->weight('semibold')->wrap(),
                TextColumn::make('parent.name')->label('Induk')->badge()->placeholder('— utama —')->sortable(),
                TextColumn::make('children_count')->counts('children')->label('Sub')->badge()->placeholder('0'),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
                IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->filters([
                SelectFilter::make('parent_id')->label('Induk')->relationship('parent', 'name'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListServiceStandards::route('/'),
            'create' => CreateServiceStandard::route('/create'),
            'edit'   => EditServiceStandard::route('/{record}/edit'),
        ];
    }
}
