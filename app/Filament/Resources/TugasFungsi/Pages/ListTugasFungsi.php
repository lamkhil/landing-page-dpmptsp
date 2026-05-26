<?php

namespace App\Filament\Resources\TugasFungsi\Pages;

use App\Filament\Resources\TugasFungsi\TugasFungsiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTugasFungsi extends ListRecords
{
    protected static string $resource = TugasFungsiResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
