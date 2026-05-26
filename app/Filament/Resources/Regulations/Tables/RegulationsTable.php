<?php

namespace App\Filament\Resources\Regulations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegulationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('doc_year', 'desc')
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->weight('semibold')->limit(60)->wrap(),
                TextColumn::make('doc_type')->label('Jenis')->badge()->sortable(),
                TextColumn::make('doc_number')->label('Nomor')->placeholder('—'),
                TextColumn::make('doc_year')->label('Tahun')->sortable(),
                IconColumn::make('is_published')->label('Publik')->boolean(),
                TextColumn::make('signed_at')->label('Ditetapkan')->date('d M Y')->placeholder('—')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('doc_type')->label('Jenis')->options([
                    'perwali' => 'Peraturan Walikota',
                    'perda'   => 'Peraturan Daerah',
                    'kepwali' => 'Keputusan Walikota',
                    'sk'      => 'Surat Keputusan',
                    'sop'     => 'SOP',
                ]),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
