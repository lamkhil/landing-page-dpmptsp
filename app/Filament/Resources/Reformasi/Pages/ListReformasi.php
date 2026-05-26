<?php

namespace App\Filament\Resources\Reformasi\Pages;

use App\Filament\Resources\Reformasi\ReformasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReformasi extends ListRecords
{
    protected static string $resource = ReformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
