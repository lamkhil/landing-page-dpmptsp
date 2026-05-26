<?php

namespace App\Filament\Resources\ServiceStandards\Pages;

use App\Filament\Resources\ServiceStandards\ServiceStandardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceStandard extends EditRecord
{
    protected static string $resource = ServiceStandardResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
