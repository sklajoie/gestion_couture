<?php

namespace App\Filament\Resources\MesurePantalons\Schemas;

use App\Models\MesurePantalon;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class MesurePantalonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextEntry::make('Type')
                    ->label('Type'),
                 TextEntry::make('designation')
                    ->label('Désignation'),
                TextEntry::make('Tour_taille')
                    ->label('Tour de taille'),
                TextEntry::make('Tour_bassin')
                    ->label('Tour de bassin'),
                TextEntry::make('Tour_cuisse')
                    ->label('Tour de cuisse'),   
                TextEntry::make('Tour_genou')
                    ->label('Tour de genou'),
                TextEntry::make('Tour_bas')
                    ->label('Tour de bas'),
                TextEntry::make('Hauteur_genou')
                    ->label('Hauteur de genou'),
                TextEntry::make('Hauteur_cheville')
                    ->label('Hauteur de cheville'),
                TextEntry::make('Entre_jambe')
                    ->label('Entre-jambe'),
                TextEntry::make('Longueur_pantalon')
                    ->label('Longueur de pantalon'),
                TextEntry::make('Description')
                    ->label('Description'),   

                TextEntry::make('user.name')
                    ->label('Utilisateur'),
                TextEntry::make('nombre')
                    ->label('Nombre'),
                ImageEntry::make('Model_mesure') 
                    ->disk('public')
                    ->imageWidth(200)
                      ->url(fn ($state) => asset('storage/' . $state))
                     ->openUrlInNewTab()
                    ->hidden(fn (MesurePantalon $record) => empty($record->Model_mesure))
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
                    TableColumn::make('RESPONSABLE'),
                    TableColumn::make('ATELIER'),
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
            ]);
    }
}
