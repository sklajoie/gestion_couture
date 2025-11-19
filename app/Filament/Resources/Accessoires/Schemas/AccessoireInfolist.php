<?php

namespace App\Filament\Resources\Accessoires\Schemas;

use App\Models\Accessoire;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AccessoireInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               TextEntry::make('nom')
                    ->placeholder('-')
                    ->label('Nom du produit'),
                TextEntry::make('code_barre')
                    ->placeholder('-')
                    ->label('Code barre'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull()
                    ->label('Description'),
                TextEntry::make('prix_achat')
                    ->numeric()
                    ->placeholder('-')
                    ->label('Prix d\'achat'),
                TextEntry::make('prix_vente')
                    ->numeric()
                    ->placeholder('-')
                    ->label('Prix de vente'),
                TextEntry::make('stock_minimum')
                    ->numeric()
                    ->placeholder('-')
                    ->label('Stock minimum'),
                TextEntry::make('stock')
                    ->numeric()
                    ->placeholder('-')
                    ->label('Stock'),
                TextEntry::make('marque.nom')
                    ->label('Marque')
                    ->placeholder('-'),
                TextEntry::make('taille.nom')
                    ->label('Taille')
                    ->placeholder('-'),
                TextEntry::make('couleur.nom')
                    ->label('Couleur')
                    ->placeholder('-'),
                TextEntry::make('unite')
                    ->placeholder('-')
                    ->label('Unité'),
                TextEntry::make('categorieProduit.nom')
                    ->placeholder('-')
                    ->label('Catégorie'),
                TextEntry::make('user.name')
                    ->placeholder('-')
                    ->label('Utilisateur'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Créé le'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Mis à jour le'),
                ImageEntry::make('image') 
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    // ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                     ->openUrlInNewTab()
                    ->hidden(fn (Accessoire $record) => empty($record->image))
                    ->label('Image'),
            ]);
    }
}
