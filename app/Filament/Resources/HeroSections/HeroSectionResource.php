<?php

namespace App\Filament\Resources\HeroSections;

use App\Domain\Hero\Models\HeroSection;
use App\Filament\Resources\HeroSections\Pages\CreateHeroSection;
use App\Filament\Resources\HeroSections\Pages\EditHeroSection;
use App\Filament\Resources\HeroSections\Pages\ListHeroSections;
use App\Filament\Resources\HeroSections\RelationManagers\HighlightsRelationManager;
use App\Filament\Resources\HeroSections\Schemas\HeroSectionForm;
use App\Filament\Resources\HeroSections\Tables\HeroSectionsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HeroSectionResource extends Resource
{
    protected static ?string $model = HeroSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Beranda';

    protected static ?string $navigationLabel = 'Hero';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Hero';

    protected static ?string $pluralModelLabel = 'Hero';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return HeroSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroSectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            HighlightsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHeroSections::route('/'),
            'create' => CreateHeroSection::route('/create'),
            'edit' => EditHeroSection::route('/{record}/edit'),
        ];
    }
}
