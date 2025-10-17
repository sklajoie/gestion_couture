<?php

namespace App\Filament\Resources\Approvisionnements\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApprovisionnementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference'),
                TextEntry::make('bonCommande.reference')
                    ->label('Bon de commande')
                    ->numeric(),
                TextEntry::make('date_operation')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->label('Opérateur')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime()
                    ->placeholder('-'),

                RepeatableEntry::make('details')
                    ->label("Détails de l'approvisionnement")
                    ->schema([
                        TextEntry::make('produit.nom')->label('Produit'),
                        TextEntry::make('quantite')->label('Quantité'),
                        TextEntry::make('prix_unitaire')->label('Prix unitaire'),
                        TextEntry::make('total')->label('Total'),
                    ])
                    ->columns(4)
                    // ->collapsible()
                    // ->collapsed()


            ]);
    }
}
