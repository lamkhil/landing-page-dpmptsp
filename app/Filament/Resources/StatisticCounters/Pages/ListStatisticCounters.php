<?php

namespace App\Filament\Resources\StatisticCounters\Pages;

use App\Filament\Resources\StatisticCounters\StatisticCounterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStatisticCounters extends ListRecords
{
    protected static string $resource = StatisticCounterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
