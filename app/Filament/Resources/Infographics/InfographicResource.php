<?php

namespace App\Filament\Resources\Infographics;

use App\Domain\Content\Models\Infographic;
use App\Filament\Resources\Infographics\Pages\CreateInfographic;
use App\Filament\Resources\Infographics\Pages\EditInfographic;
use App\Filament\Resources\Infographics\Pages\ListInfographics;
use App\Filament\Resources\Infographics\Schemas\InfographicForm;
use App\Filament\Resources\Infographics\Tables\InfographicsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InfographicResource extends Resource
{
    protected static ?string $model = Infographic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartPie;

    protected static string|\UnitEnum|null $navigationGroup = 'Informasi Publik';

    protected static ?string $navigationLabel = 'Infografis';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Infografis';

    protected static ?string $pluralModelLabel = 'Infografis';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return InfographicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InfographicsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInfographics::route('/'),
            'create' => CreateInfographic::route('/create'),
            'edit' => EditInfographic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Pertahankan global scope "infographic"; lepaskan scope soft-delete saja.
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
