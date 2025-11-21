<?php

namespace App\Filament\Resources\Employes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextEntry::make('nom')
                    ->label('Nom'),
                TextEntry::make('prenom')
                    ->label('Prénom'),
                TextEntry::make('email')
                    ->label('Email'),       
                TextEntry::make('contact')
                    ->label('Contact'),       
                TextEntry::make('adresse')
                    ->label('Adresse'),   
                TextEntry::make('poste')
                    ->label('Poste'),   
                TextEntry::make('date_embauche')
                    ->label('Date d\'embauche')
                     ->date(),
                TextEntry::make('atelier.nom')
                    ->label('Atelier'),
                TextEntry::make('agence.nom')
                    ->label('Agence'),
                    
            ]);
    }
}
