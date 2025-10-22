<?php

namespace App\Filament\Resources\StockEntreprises\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockEntrepriseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('designation')->label('Désignation'),
                TextEntry::make('code_barre')->label('Code barre'),
                TextEntry::make('reference')->label('Référence'),
                TextEntry::make('stock')->label('Stock'),
                TextEntry::make('prix')->label('Prix'),
                TextEntry::make('stock_alerte')->label('Stock alerte'),  
                TextEntry::make('couleur.nom')->label('Couleur')->placeholder('-'),
                TextEntry::make('taille.nom')->label('Taille')->placeholder('-'),
                TextEntry::make('user.name')->label('Utilisateur')->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
