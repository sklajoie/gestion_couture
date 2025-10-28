<?php

namespace App\Filament\Resources\Accessoires\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AccessoireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Select::make('categorie_produit_id')
                    ->relationship('categorieProduit','nom')
                    ->label('Catégorie')
                    ->searchable(['type', 'nom'])
                     ->preload(),
                Select::make('marque_id')
                    ->relationship('marque','nom')
                    ->label('Marque')
                    ->searchable(['id', 'nom'])
                     ->preload(),
                Select::make('taille_id')
                    ->relationship('taille','nom')
                    ->label('Taille')
                    ->searchable(['id', 'nom'])
                     ->preload(),
                Select::make('couleur_id')
                    ->relationship('couleur','nom')
                    ->label('Couleur')
                    ->searchable(['id', 'nom'])
                     ->preload(),
                TextInput::make('nom')
                    ->label('Nom Accessoire')
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
                // Checkbox::make('stockable')
                //     ->label('Stockable'),
                Radio::make('stockable')
                ->label('Produit Stockable?')
                ->boolean()
                ->default(false)
                ->inline(),
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
