<?php

namespace App\Filament\Resources\StatisticCounters;

use App\Domain\Statistic\Models\StatisticCounter;
use App\Filament\Resources\StatisticCounters\Pages\CreateStatisticCounter;
use App\Filament\Resources\StatisticCounters\Pages\EditStatisticCounter;
use App\Filament\Resources\StatisticCounters\Pages\ListStatisticCounters;
use App\Filament\Resources\StatisticCounters\Schemas\StatisticCounterForm;
use App\Filament\Resources\StatisticCounters\Tables\StatisticCountersTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StatisticCounterResource extends Resource
{
    protected static ?string $model = StatisticCounter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Statistik';

    protected static ?string $navigationLabel = 'Counter Cards';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Counter';

    protected static ?string $pluralModelLabel = 'Counters';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return StatisticCounterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StatisticCountersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStatisticCounters::route('/'),
            'create' => CreateStatisticCounter::route('/create'),
            'edit' => EditStatisticCounter::route('/{record}/edit'),
        ];
    }
}
