<?php

namespace App\Filament\Resources\FooterLinks;

use App\Domain\Footer\Models\FooterLink;
use App\Filament\Resources\FooterLinks\Pages\CreateFooterLink;
use App\Filament\Resources\FooterLinks\Pages\EditFooterLink;
use App\Filament\Resources\FooterLinks\Pages\ListFooterLinks;
use App\Filament\Resources\FooterLinks\Schemas\FooterLinkForm;
use App\Filament\Resources\FooterLinks\Tables\FooterLinksTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FooterLinkResource extends Resource
{
    protected static ?string $model = FooterLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|\UnitEnum|null $navigationGroup = 'Tampilan & SEO';

    protected static ?string $navigationLabel = 'Footer Links';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Footer Link';

    protected static ?string $pluralModelLabel = 'Footer Links';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return FooterLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FooterLinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFooterLinks::route('/'),
            'create' => CreateFooterLink::route('/create'),
            'edit' => EditFooterLink::route('/{record}/edit'),
        ];
    }
}
