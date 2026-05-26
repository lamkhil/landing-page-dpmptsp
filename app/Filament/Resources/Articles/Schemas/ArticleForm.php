<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Artikel')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Konten')
                        ->columns(2)
                        ->schema([
                            // type diset otomatis ke "article" oleh model Article.
                            Select::make('category_id')
                                ->label('Topik')
                                ->options(fn () => Category::query()->where('type', 'post')->pluck('name', 'id'))
                                ->searchable()
                                ->preload(),
                            Toggle::make('is_featured')->label('Jadikan artikel pilihan?'),
                            TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->columnSpanFull(),
                            Textarea::make('excerpt')->label('Ringkasan')->maxLength(500)->rows(2)->columnSpanFull()
                                ->helperText('Tampil di kartu artikel & meta deskripsi.'),
                            FileUpload::make('cover_path')->label('Gambar Sampul')->image()->directory('posts/covers')->maxSize(4096)->imageEditor()->columnSpanFull(),
                            RichEditor::make('body')->label('Isi Artikel')->required()->columnSpanFull()
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
                            DateTimePicker::make('published_at')->label('Tanggal Terbit'),
                            DateTimePicker::make('scheduled_at')->label('Jadwalkan')->helperText('Untuk auto-publish via job di kemudian hari.'),
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
