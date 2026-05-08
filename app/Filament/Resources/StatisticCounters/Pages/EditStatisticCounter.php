<?php

namespace App\Filament\Resources\StatisticCounters\Pages;

use App\Filament\Resources\StatisticCounters\StatisticCounterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStatisticCounter extends EditRecord
{
    protected static string $resource = StatisticCounterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
