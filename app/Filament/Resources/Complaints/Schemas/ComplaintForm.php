<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Domain\Complaint\Models\Complaint;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Pengaduan')
                ->columns(2)
                ->components([
                    TextInput::make('ticket_no')->disabled()->dehydrated(false),
                    TextInput::make('channel')->disabled()->dehydrated(false),
                    TextInput::make('full_name')->label('Nama Pelapor')->disabled()->dehydrated(false),
                    TextInput::make('email')->disabled()->dehydrated(false),
                    TextInput::make('phone')->disabled()->dehydrated(false),
                    TextInput::make('subject')->disabled()->dehydrated(false)->columnSpanFull(),
                    Textarea::make('body')->label('Isi Pengaduan')->disabled()->dehydrated(false)->rows(6)->columnSpanFull(),
                ]),

            Section::make('Penanganan')
                ->columns(2)
                ->components([
                    Select::make('status')
                        ->required()
                        ->options([
                            Complaint::STATUS_OPEN        => 'Open',
                            Complaint::STATUS_IN_PROGRESS => 'In Progress',
                            Complaint::STATUS_RESOLVED    => 'Resolved',
                            Complaint::STATUS_REJECTED    => 'Rejected',
                        ]),
                    Select::make('handled_by')
                        ->label('PIC')
                        ->options(fn () => User::where('is_active', true)->pluck('name', 'id'))
                        ->searchable(),
                    Textarea::make('response')->label('Tanggapan')->rows(5)->columnSpanFull(),
                    DateTimePicker::make('responded_at')->label('Waktu Tanggap'),
                ]),
        ]);
    }
}
