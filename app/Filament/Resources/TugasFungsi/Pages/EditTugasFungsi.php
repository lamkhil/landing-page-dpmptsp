<?php

namespace App\Filament\Resources\TugasFungsi\Pages;

use App\Filament\Resources\TugasFungsi\TugasFungsiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTugasFungsi extends EditRecord
{
    protected static string $resource = TugasFungsiResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
