<?php

namespace App\Filament\Resources\MesureRobes\Schemas;

use App\Models\MesureRobe;
use Dom\Text;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class MesureRobeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('Type')
                    ->label('Type'),
                TextEntry::make('Epaule')
                    ->label('Épaule'),
                TextEntry::make('Tour_poitrine')
                    ->label('Tour poitrine'),
                TextEntry::make('Tour_taille')
                    ->label('Tour taille'),
                TextEntry::make('Tour_bassin')
                    ->label('Tour bassin'),
                TextEntry::make('Longueur_bassin')
                    ->label('Longueur bassin'),    
                TextEntry::make('Carrure_dos')
                    ->label('Carrure dos'),    
                TextEntry::make('Longueur_buste')
                    ->label('Longueur buste'),    
                TextEntry::make('Longueur_manche')
                    ->label('Longueur manche'),
                TextEntry::make('Tour_manche')
                    ->label('Tour manche'),
                TextEntry::make('Tour_bras') 
                    ->label('Tour bras'),   
                TextEntry::make('Longueur_robe')
                    ->label('Longueur robe'),
                TextEntry::make('Description')
                    ->label('Description'),
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
                ImageEntry::make('Model_mesure') 
                    ->disk('public')
                    ->imageWidth(200)
                      ->url(fn ($state) => asset('storage/' . $state))
                     ->openUrlInNewTab()
                    ->hidden(fn (MesureRobe $record) => empty($record->Model_mesure))
                    ->label('Modèle'),

                 RepeatableEntry::make('etapeMesures')
                        ->table([
                    TableColumn::make('ETAPE')
                        ->alignment(Alignment::Center),
                    TableColumn::make('RESPONSABLE'),
                    TableColumn::make('STATUT'),
                    TableColumn::make('DATE DEBUT'),
                    TableColumn::make('DATE FIN'),
                    TableColumn::make('TEMPS MIS'),
                        ])
                        ->schema([
                            TextEntry::make('etapeProduction.nom'),
                            TextEntry::make('responsable.name'),
                            IconEntry::make('is_completed')
                            ->boolean(),
                            TextEntry::make('date_debut')
                                ->dateTime('j M , Y H:i:s'),
                            TextEntry::make('date_fin')
                                ->dateTime('j M , Y H:i:s'),
                            TextEntry::make('temp_mis')
                               ->html(),
                        
                                ]),
                RepeatableEntry::make('produitCouture')
                        ->table([
                            TableColumn::make('PRODUIT'),
                            TableColumn::make('QTE'),
                            TableColumn::make('PRIX'),
                            TableColumn::make('TOTAL'),
                        ])
                        ->schema([
                            TextEntry::make('produit.nom'),
                            TextEntry::make('prix_unitaire'),
                            TextEntry::make('quantite'),
                            TextEntry::make('total'),
                         ]),
            ]);
    }
}
