<?php

namespace App\Filament\Resources\ClotureCaisses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClotureCaisseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('montant_devis')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('montant_vente')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('montant_entre')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('montant_solde')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('agence.nom')
                    ->numeric(),
                TextEntry::make('user.name')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
