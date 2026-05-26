<?php

namespace App\Filament\Resources\Infographics\Pages;

use App\Filament\Resources\Infographics\InfographicResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInfographics extends ListRecords
{
    protected static string $resource = InfographicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
