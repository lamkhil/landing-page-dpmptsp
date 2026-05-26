<?php

namespace App\Filament\Resources\ServiceStandardDocuments\Pages;

use App\Filament\Resources\ServiceStandardDocuments\ServiceStandardDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceStandardDocuments extends ListRecords
{
    protected static string $resource = ServiceStandardDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
