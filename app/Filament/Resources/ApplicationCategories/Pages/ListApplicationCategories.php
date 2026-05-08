<?php

namespace App\Filament\Resources\ApplicationCategories\Pages;

use App\Filament\Resources\ApplicationCategories\ApplicationCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplicationCategories extends ListRecords
{
    protected static string $resource = ApplicationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
