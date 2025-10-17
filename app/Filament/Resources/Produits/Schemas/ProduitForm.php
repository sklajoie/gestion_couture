<?php

namespace App\Filament\Resources\Produits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('categorie_produit_id')
                ->relationship('categorie','type')
                ->relationship('categorie','nom')
                ->label('Catégorie')
                ->searchable(),
                TextInput::make('nom')
                    ->label('Nom du produit')
                    ->required(),
                // TextInput::make('code_barre')
                //     ->label('Code barre')
                //     ->required(),
               
                TextInput::make('prix_achat')
                    ->label('Prix d\'achat')
                    ->numeric(),
                TextInput::make('prix_vente')
                    ->label('Prix de vente')
                    ->numeric(),
                TextInput::make('stock_minimum')
                    ->label('Stock minimum')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('unite')
                    ->required()
                    ->options([
                            'kg' => 'Kilogramme (kg)',
                            'g' => 'Gramme (g)',
                            'l' => 'Litre (l)',
                            'ml' => 'Millilitre (ml)',
                            'm' => 'Mètre (m)',
                            'cm' => 'Centimètre (cm)',
                            'piece' => 'Pièce',
                            'unite' => 'Unité',
                        ])
                    ->default('unite'),


                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
            ]);
    }
}
