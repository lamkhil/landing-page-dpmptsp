<?php

namespace App\Filament\Resources\ApplicationCategories;

use App\Domain\Application\Models\ApplicationCategory;
use App\Filament\Resources\ApplicationCategories\Pages\CreateApplicationCategory;
use App\Filament\Resources\ApplicationCategories\Pages\EditApplicationCategory;
use App\Filament\Resources\ApplicationCategories\Pages\ListApplicationCategories;
use App\Filament\Resources\ApplicationCategories\Schemas\ApplicationCategoryForm;
use App\Filament\Resources\ApplicationCategories\Tables\ApplicationCategoriesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApplicationCategoryResource extends Resource
{
    protected static ?string $model = ApplicationCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Aplikasi Publik';

    protected static ?string $navigationLabel = 'Kategori Aplikasi';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kategori Aplikasi';

    protected static ?string $pluralModelLabel = 'Kategori Aplikasi';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ApplicationCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicationCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplicationCategories::route('/'),
            'create' => CreateApplicationCategory::route('/create'),
            'edit' => EditApplicationCategory::route('/{record}/edit'),
        ];
    }
}
