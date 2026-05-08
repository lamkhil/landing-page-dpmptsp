<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identitas')
                ->columns(2)
                ->components([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('email')->required()->email()->unique(ignoreRecord: true)->maxLength(255),
                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->minLength(8)
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->helperText('Kosongkan saat edit untuk tetap menggunakan password lama.'),
                    Toggle::make('is_active')->label('Aktif?')->default(true),
                ]),

            Section::make('Akses')
                ->components([
                    Select::make('roles')
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->options(fn () => Role::pluck('name', 'name'))
                        ->preload()
                        ->required()
                        ->saveRelationshipsUsing(function ($component, $state, $record) {
                            $record?->syncRoles($state);
                        })
                        ->helperText('super-admin = akses penuh; admin/editor/operator/viewer mengikuti permission seeder.'),
                ]),
        ]);
    }
}
