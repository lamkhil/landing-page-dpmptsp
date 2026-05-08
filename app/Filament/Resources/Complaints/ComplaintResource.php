<?php

namespace App\Filament\Resources\Complaints;

use App\Domain\Complaint\Models\Complaint;
use App\Filament\Resources\Complaints\Pages\EditComplaint;
use App\Filament\Resources\Complaints\Pages\ListComplaints;
use App\Filament\Resources\Complaints\Schemas\ComplaintForm;
use App\Filament\Resources\Complaints\Tables\ComplaintsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|\UnitEnum|null $navigationGroup = 'Pelayanan Publik';

    protected static ?string $navigationLabel = 'Pengaduan';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Pengaduan';

    protected static ?string $pluralModelLabel = 'Pengaduan';

    protected static ?string $recordTitleAttribute = 'ticket_no';

    public static function form(Schema $schema): Schema
    {
        return ComplaintForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        // Pengaduan hanya dibuat dari form publik /pengaduan/lapor.
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComplaints::route('/'),
            'edit' => EditComplaint::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()
            ->whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_IN_PROGRESS])
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
