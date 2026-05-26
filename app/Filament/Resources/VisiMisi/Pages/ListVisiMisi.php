<?php

namespace App\Filament\Resources\VisiMisi\Pages;

use App\Filament\Resources\VisiMisi\VisiMisiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVisiMisi extends ListRecords
{
    protected static string $resource = VisiMisiResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
