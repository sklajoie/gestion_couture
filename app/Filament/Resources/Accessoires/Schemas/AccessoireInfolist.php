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
                TextEntry::make('categorieProduit.nom')
                    ->placeholder('-'),
                TextEntry::make('marque.nom')
                    ->placeholder('-'),
                TextEntry::make('taille.nom')
                    ->placeholder('-'),
                TextEntry::make('couleur.nom')
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
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
