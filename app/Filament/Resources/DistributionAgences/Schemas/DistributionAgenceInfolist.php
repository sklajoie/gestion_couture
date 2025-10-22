<?php

namespace App\Filament\Resources\DistributionAgences\Schemas;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DistributionAgenceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')->label('Référence'),
                TextEntry::make('date_operation')->label('Date d\'opération'),
                TextEntry::make('user.name')->label('Utilisateur'),
                IconEntry::make('is_valide')->label('Est Valide')->boolean(),

                RepeatableEntry::make('detailDistributionAgences')
                    ->label("Détails Distribution Agence")
                    ->schema([
                        TextEntry::make('stockEntreprise.code_barre')->label('Code Barre'),
                        TextEntry::make('stockEntreprise.reference')->label('Référence'),
                        TextEntry::make('stockEntreprise.couleur.nom')->label('Couleur'),
                        TextEntry::make('stockEntreprise.taille.nom')->label('Taille'),
                        TextEntry::make('stockEntreprise.prix')->label('Prix (XOF)'),
                        TextEntry::make('quantite')->label('Quantité'),
                    ])
                    ->columns(6)
                    ->columnSpanFull()
                    ]);


    }


}
