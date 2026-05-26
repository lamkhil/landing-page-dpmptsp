<?php

namespace App\Filament\Resources\OrgUnits;

use App\Domain\Profil\Models\OrgUnit;
use App\Filament\Resources\OrgUnits\Pages\CreateOrgUnit;
use App\Filament\Resources\OrgUnits\Pages\EditOrgUnit;
use App\Filament\Resources\OrgUnits\Pages\ListOrgUnits;
use App\Filament\Resources\OrgUnits\Schemas\OrgUnitForm;
use App\Filament\Resources\OrgUnits\Tables\OrgUnitsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrgUnitResource extends Resource
{
    protected static ?string $model = OrgUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil';

    protected static ?string $navigationLabel = 'Struktur Organisasi';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Unit Organisasi';

    protected static ?string $pluralModelLabel = 'Struktur Organisasi';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OrgUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrgUnitsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListOrgUnits::route('/'),
            'create' => CreateOrgUnit::route('/create'),
            'edit'   => EditOrgUnit::route('/{record}/edit'),
        ];
    }
}
