<?php

namespace App\Filament\Resources\ServiceStandards\Pages;

use App\Filament\Resources\ServiceStandards\ServiceStandardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceStandards extends ListRecords
{
    protected static string $resource = ServiceStandardResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
