<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Post')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Konten')
                        ->columns(2)
                        ->schema([
                            Select::make('type')
                                ->label('Tipe')
                                ->required()
                                ->options([
                                    Post::TYPE_NEWS         => 'Berita',
                                    Post::TYPE_ANNOUNCEMENT => 'Pengumuman',
                                    Post::TYPE_ARTICLE      => 'Artikel',
                                    Post::TYPE_INFOGRAFIS   => 'Infografis',
                                    Post::TYPE_PROFIL       => 'Profil',
                                    Post::TYPE_ZI           => 'Zona Integritas',
                                ])
                                ->default(Post::TYPE_NEWS),
                            Select::make('category_id')
                                ->label('Kategori')
                                ->options(fn () => Category::query()->pluck('name', 'id'))
                                ->searchable(),
                            TextInput::make('title')->required()->maxLength(255)->columnSpanFull()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->columnSpanFull(),
                            Textarea::make('excerpt')->maxLength(500)->rows(2)->columnSpanFull(),
                            FileUpload::make('cover_path')->label('Cover')->image()->directory('posts/covers')->maxSize(4096)->imageEditor()->columnSpanFull(),
                            RichEditor::make('body')->label('Isi')->required()->columnSpanFull()
                                ->fileAttachmentsDirectory('posts/inline')
                                ->fileAttachmentsVisibility('public'),
                        ]),

                    Tab::make('Publikasi')
                        ->columns(2)
                        ->schema([
                            Select::make('status')->required()->options([
                                Post::STATUS_DRAFT     => 'Draft',
                                Post::STATUS_PUBLISHED => 'Published',
                                Post::STATUS_ARCHIVED  => 'Archived',
                            ])->default(Post::STATUS_DRAFT),
                            Toggle::make('is_featured')->label('Featured?'),
                            DateTimePicker::make('published_at')->label('Publish at'),
                            DateTimePicker::make('scheduled_at')->label('Scheduled at')->helperText('Untuk auto-publish via job di kemudian hari.'),
                        ]),

                    Tab::make('SEO')
                        ->columns(1)
                        ->schema([
                            TextInput::make('meta_title')->maxLength(255),
                            Textarea::make('meta_description')->rows(3)->maxLength(500),
                            FileUpload::make('og_image_path')->label('OG Image')->image()->directory('posts/og')->maxSize(2048),
                        ]),
                ]),
        ]);
    }
}
