<?php

namespace App\Filament\Resources\StockEntreprises\Schemas;

use Dom\Text;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockEntrepriseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('designation')->label('Désignation'),
                TextInput::make('code_barre')->label('Code barre')->unique(),
                TextInput::make('reference')->label('Référence'),
                TextInput::make('stock')->label('Stock')->numeric()->default(0),
                TextInput::make('prix')->label('Prix')->numeric()->default(0),
                TextInput::make('stock_alerte')->label('Stock alerte')->numeric()->default(0),  
                Select::make('couleur_id')
                    ->relationship('couleur', 'nom')
                    ->label('Couleur')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Select::make('taille_id')
                    ->relationship('taille', 'nom')
                    ->label('Taille')
                    ->searchable()  
                    ->preload()
                    ->nullable(),
                    
            ]);
    }
}
