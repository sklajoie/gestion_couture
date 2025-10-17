<?php

namespace App\Filament\Resources\Produits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProduitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nom'),
                TextEntry::make('code_barre'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('prix_achat')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('prix_vente')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('stock_minimum')
                    ->numeric(),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('unite'),
                TextEntry::make('categorie.nom')
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
