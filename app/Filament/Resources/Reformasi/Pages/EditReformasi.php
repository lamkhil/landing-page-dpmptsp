<?php

namespace App\Filament\Resources\Reformasi\Pages;

use App\Filament\Resources\Reformasi\ReformasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReformasi extends EditRecord
{
    protected static string $resource = ReformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
