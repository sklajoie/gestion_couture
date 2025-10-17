<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required(),
                TextInput::make('telephone')
                     ->label('Téléphone')
                     ->required(),
                TextInput::make('email')
                     ->label('Email'),  
                TextInput::make('ville')
                     ->label('Ville'),      
                TextInput::make('adresse')
                     ->label('Adresse'),

                Hidden::make('user_id')
                ->default(Auth::id()),
                

            ]);
    }
}
