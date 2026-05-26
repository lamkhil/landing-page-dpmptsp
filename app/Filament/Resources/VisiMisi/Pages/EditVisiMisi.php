<?php

namespace App\Filament\Resources\VisiMisi\Pages;

use App\Filament\Resources\VisiMisi\VisiMisiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVisiMisi extends EditRecord
{
    protected static string $resource = VisiMisiResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
