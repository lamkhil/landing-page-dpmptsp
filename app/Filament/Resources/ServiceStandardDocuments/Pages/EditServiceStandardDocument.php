<?php

namespace App\Filament\Resources\ServiceStandardDocuments\Pages;

use App\Filament\Resources\ServiceStandardDocuments\ServiceStandardDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceStandardDocument extends EditRecord
{
    protected static string $resource = ServiceStandardDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
