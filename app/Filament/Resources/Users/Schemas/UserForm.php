<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'user agence' => 'Utilisateur Agence',
                        'user atelier' => 'Utilisateur Atelier',
                        'Responsable Atelier' => 'Responsable Atelier',
                        'Responsable Agence' => 'Responsable Agence',
                    ])
                    ->required()
                    ->default('user agence'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('password')
                    ->password()
                     ->revealable()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Select::make('employe_id')
                    ->relationship('employe', 'nom')
                    ->label('Employé')
                    ->searchable()
                    ->preload(),
                Toggle::make('active')
                    ->label('Actif')
                    ->default(true)
                    ->onIcon(Heroicon::User)
                    ->offIcon(Heroicon::Bolt)
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }
}
