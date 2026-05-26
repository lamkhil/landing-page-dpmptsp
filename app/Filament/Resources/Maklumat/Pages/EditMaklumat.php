<?php

namespace App\Filament\Resources\Maklumat\Pages;

use App\Filament\Resources\Maklumat\MaklumatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMaklumat extends EditRecord
{
    protected static string $resource = MaklumatResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
