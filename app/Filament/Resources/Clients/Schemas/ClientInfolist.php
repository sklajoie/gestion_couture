<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nom')
                    ->label('Nom'),
                TextEntry::make('telephone')
                     ->label('Téléphone'),
                TextEntry::make('email')
                     ->label('Email'),  
                TextEntry::make('ville')
                     ->label('Ville'),      
                TextEntry::make('adresse')
                     ->label('Adresse'),  
            ]);
    }
}
