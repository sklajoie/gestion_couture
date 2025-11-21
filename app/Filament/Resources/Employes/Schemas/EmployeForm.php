<?php

namespace App\Filament\Resources\Employes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class EmployeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('atelier_id')
                    ->relationship('atelier', 'nom')
                    ->label('Atelier')
                    ->searchable()
                    ->preload(),
                Select::make('agence_id')
                    ->relationship('agence', 'nom')
                    ->label('Agence')
                    ->searchable()
                    ->preload(),
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('prenom')
                    ->label('Prénom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('contact')
                    ->label('Contact')    
                    ->required(),       
                TextInput::make('email')
                    ->label('Email') 
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
