<?php

namespace App\Filament\Resources\Maklumat\Pages;

use App\Filament\Resources\Maklumat\MaklumatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaklumat extends ListRecords
{
    protected static string $resource = MaklumatResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
