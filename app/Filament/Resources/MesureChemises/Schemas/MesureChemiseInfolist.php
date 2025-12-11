<?php

namespace App\Filament\Resources\MesureChemises\Schemas;

use App\Models\MesureChemise;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class MesureChemiseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                  
                //   TextEntry::make('etapeMesures.nom')
                //     ->label('Etape'),
                    
                TextEntry::make('Type')
                    ->label('Type'),
                TextEntry::make('designation')
                    ->label('Désignation'),
                   
                TextEntry::make('Tour_cou')
                    ->label('Tour du cou'),
                   
                TextEntry::make('Tour_poitrine')
                    ->label('Tour poitrine'),
                   
                TextEntry::make('Tour_taille')
                    ->label('Tour taille'),
                   
                TextEntry::make('Tour_bassin')
                    ->label('Tour bassin'),
                   
                TextEntry::make('Largeur_epaule')
                    ->label('Largeur épaule'),
                          
                TextEntry::make('Longueur_manche')
                    ->label('Longueur manche'),
                   
                TextEntry::make('Tour_bas')
                    ->label('Tour bas'),
                   
                TextEntry::make('Tour_poignet')
                    ->label('Tour poignet'),
                TextEntry::make('Longueur_chemise')
                    ->label('Longueur chemise'),
                   
                
                TextEntry::make('Description')
                    ->label('Description'), 
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
                TextEntry::make('nombre')
                    ->label('Nombre'),

                ImageEntry::make('Model_mesure') 
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                     ->openUrlInNewTab()
                    ->hidden(fn (MesureChemise $record) => empty($record->Model_mesure))
                    ->label('Modèle'),

               RepeatableEntry::make('etapeMesures')
                        ->columnSpanFull()
                        ->getStateUsing(fn ($record) =>
                          $record->etapeMesures()
                            ->with(['etapeProduction', 'responsable'])
                            ->orderBy('id', 'asc')
                            ->get()
                             )
                        ->table([
                    TableColumn::make('ETAPE')
                        ->alignment(Alignment::Center),
                    TableColumn::make('ATELIER'),
                    TableColumn::make('RESPONSABLE'),
                    TableColumn::make('STATUT'),
                    TableColumn::make('DATE DEBUT'),
                    TableColumn::make('DATE FIN'),
                    TableColumn::make('TEMPS MIS'),
                    TableColumn::make('MONTANT'),
                        ])
                        ->schema([
                            TextEntry::make('etapeProduction.nom'),
                            TextEntry::make('atelier.nom'),
                            TextEntry::make('responsable.nom')
                             ->formatStateUsing(function ($state, $record) {
                        return $record->responsable
                            ? "{$record->responsable->prenom} {$record->responsable->nom}"
                            : '-';
                            }),
                            IconEntry::make('is_completed')
                            ->boolean(),
                            TextEntry::make('date_debut')
                                ->dateTime('j M , Y H:i:s'),
                            TextEntry::make('date_fin')
                                ->dateTime('j M , Y H:i:s'),
                            TextEntry::make('temp_mis')
                            ->html(),
                            TextEntry::make('montant'),
                        
                                ]),
                RepeatableEntry::make('produitCouture')
                        ->columnSpanFull()
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
                    
                    // ->schema([
                    //     TextEntry::make('etapeProduction.nom')->label('Étape'),
                    //     TextEntry::make('responsable.name')->label('Responsable'),
                    //     TextEntry::make('is_completed')
                    //      ->formatStateUsing(fn ($state) => $state ? 'Terminée ✅ ' : 'En cours ⏳')
                    //     ->label('Terminée'),
                    // ]) ->columns(3)->grid(5)

            ]);
            
    }
}
