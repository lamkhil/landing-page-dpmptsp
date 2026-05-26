<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Domain\Content\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Dokumen')
                ->columns(2)
                ->schema([
                    TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                    Select::make('category_id')->label('Kategori')
                        ->options(fn () => Category::query()->pluck('name', 'id'))->searchable()->placeholder('Tanpa kategori'),
                    Toggle::make('is_published')->label('Dipublikasikan')->default(true),
                    Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                    FileUpload::make('file_path')->label('Berkas')->directory('documents')
                        ->maxSize(20480)->downloadable()->openable()->columnSpanFull()
                        // Persist real mime + size so the public download center can show them.
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $set('mime', $state->getMimeType());
                                $set('size_bytes', $state->getSize());
                            }
                        }),
                ]),
        ]);
    }
}
