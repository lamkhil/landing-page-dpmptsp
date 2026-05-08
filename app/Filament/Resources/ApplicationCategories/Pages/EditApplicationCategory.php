<?php

namespace App\Filament\Resources\ApplicationCategories\Pages;

use App\Filament\Resources\ApplicationCategories\ApplicationCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApplicationCategory extends EditRecord
{
    protected static string $resource = ApplicationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
