<?php

namespace App\Filament\Resources\Fournisseurs\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FournisseurForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom du fournisseur')
                    ->required(),
                TextInput::make('contact')
                    ->label('Contact')
                    ->required(),
                TextInput::make('telephone')
                    ->label('Téléphone')
                    ->required(),
                TextInput::make('adresse')
                    ->label('Adresse')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
            ]);
    }
}
