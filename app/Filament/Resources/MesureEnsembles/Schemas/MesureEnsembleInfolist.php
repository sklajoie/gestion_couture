<?php

namespace App\Filament\Resources\MesureEnsembles\Schemas;

use App\Models\MesureEnsemble;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class MesureEnsembleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //////chemise//////
                TextEntry::make('Type')
                    ->label('Type'),
                   
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

                //////pantalon//////
                TextEntry::make('Tour_taille_P')
                    ->label('Tour taille'),
                TextEntry::make('Tour_bassin_P')
                    ->label('Tour bassin'),
                TextEntry::make('Tour_cuisse')
                    ->label('Tour cuisse'),   
                TextEntry::make('Tour_genou')
                    ->label('Tour genou'),
                TextEntry::make('Tour_bas_p')
                    ->label('Tour bas'),
                TextEntry::make('Hauteur_genou')
                    ->label('Hauteur genou'),
                TextEntry::make('Hauteur_cheville')
                    ->label('Hauteur cheville'),
                TextEntry::make('Entre_jambe')
                    ->label('Entre-jambe'),
                TextEntry::make('Longueur_pantalon')
                    ->label('Longueur pantalon'),
                TextEntry::make('Description')
                    ->label('Description'),   

                TextEntry::make('user.name')
                    ->label('Utilisateur'),

                ImageEntry::make('Model_mesure') 
                    ->disk('public')
                    ->imageWidth(200)
                      ->url(fn ($state) => asset('storage/' . $state))
                     ->openUrlInNewTab()
                    ->hidden(fn (MesureEnsemble $record) => empty($record->Model_mesure))
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
