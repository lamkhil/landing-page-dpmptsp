<?php

namespace App\Filament\Resources\Complaints\Tables;

use App\Domain\Complaint\Models\Complaint;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('ticket_no')->badge()->copyable()->searchable(),
                TextColumn::make('full_name')->searchable()->weight('semibold'),
                TextColumn::make('channel')->badge(),
                TextColumn::make('subject')->limit(50)->wrap(),
                TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    Complaint::STATUS_OPEN        => 'gray',
                    Complaint::STATUS_IN_PROGRESS => 'warning',
                    Complaint::STATUS_RESOLVED    => 'success',
                    Complaint::STATUS_REJECTED    => 'danger',
                    default                       => 'gray',
                })->sortable(),
                TextColumn::make('handler.name')->label('PIC')->placeholder('—')->toggleable(),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    Complaint::STATUS_OPEN        => 'Open',
                    Complaint::STATUS_IN_PROGRESS => 'In Progress',
                    Complaint::STATUS_RESOLVED    => 'Resolved',
                    Complaint::STATUS_REJECTED    => 'Rejected',
                ]),
                SelectFilter::make('channel')->options([
                    'web' => 'Web', 'sp4n' => 'SP4N LAPOR', 'wbs' => 'WBS', 'email' => 'Email',
                ]),
                TrashedFilter::make(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
