<?php

namespace App\Filament\Resources\Employes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class EmployeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('prenom')
                    ->label('Prénom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')    
                    ->required()
                    ->maxLength(255),       
                TextInput::make('adresse')
                    ->label('Adresse')
                    ->maxLength(255),   
                TextInput::make('poste')
                    ->label('Poste')
                    ->maxLength(255),   
                DatePicker::make('date_embauche')
                    ->label('Date d\'embauche') 
                    ->required(),
                
                Hidden::make('user_id')
                ->default(Auth::id()),
                

            ]);
    }
}
