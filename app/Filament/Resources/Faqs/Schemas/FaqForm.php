<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Domain\Content\Models\Category;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    Select::make('category_id')
                        ->label('Kategori')
                        ->options(fn () => Category::query()->where('type', 'faq')->pluck('name', 'id'))
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')->required(),
                            TextInput::make('slug')->required(),
                        ])
                        ->createOptionUsing(fn (array $data) => Category::create($data + ['type' => 'faq'])->id),
                    TextInput::make('sort_order')->numeric()->default(0),
                    TextInput::make('question')->label('Pertanyaan')->required()->maxLength(255)->columnSpanFull(),
                    RichEditor::make('body')->label('Jawaban')->required()->columnSpanFull(),
                    Toggle::make('is_published')->label('Publish?')->default(true),
                ]),
        ]);
    }
}
