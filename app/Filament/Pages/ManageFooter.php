<?php

namespace App\Filament\Pages;

use App\Domain\Footer\Models\FooterSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageFooter extends Page
{
    protected string $view = 'filament.pages.manage-footer';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Tampilan & SEO';

    protected static ?string $navigationLabel = 'Footer & Kontak';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Footer & Kontak';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(FooterSetting::singleton()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Alamat & Kontak')
                ->columns(2)
                ->components([
                    TextInput::make('address')->label('Alamat')->maxLength(500)->columnSpanFull(),
                    TextInput::make('phone')->label('Telepon')->maxLength(64),
                    TextInput::make('email')->label('Email')->email()->maxLength(255),
                    TextInput::make('office_hours')->label('Jam Pelayanan')->maxLength(255)->columnSpanFull(),
                    Textarea::make('about_text')->label('Tentang (footer)')->rows(3)->maxLength(1000)->columnSpanFull(),
                    Textarea::make('embed_map_url')->label('Embed Maps URL')->rows(2)->columnSpanFull(),
                ]),

            Section::make('Media Sosial')
                ->components([
                    Repeater::make('social_links')
                        ->schema([
                            Select::make('platform')->options([
                                'facebook' => 'Facebook', 'instagram' => 'Instagram', 'twitter' => 'Twitter / X',
                                'youtube' => 'YouTube', 'linkedin' => 'LinkedIn', 'tiktok' => 'TikTok',
                            ])->required(),
                            TextInput::make('url')->url()->required()->maxLength(255),
                        ])
                        ->columns(2)
                        ->reorderable()
                        ->defaultItems(0)
                        ->addActionLabel('Tambah platform'),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        FooterSetting::singleton()->update($this->form->getState());
        Notification::make()->title('Footer tersimpan.')->success()->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Simpan')->action('save')->keyBindings(['mod+s']),
        ];
    }
}
