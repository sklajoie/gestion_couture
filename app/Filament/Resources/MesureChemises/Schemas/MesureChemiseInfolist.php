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
                        ->table([
                            TableColumn::make('ETAPE')
                                ->alignment(Alignment::Center),
                            TableColumn::make('RESPONSABLE'),
                            TableColumn::make('STATUT'),
                        ])
                        ->schema([
                            TextEntry::make('etapeProduction.nom'),
                            TextEntry::make('responsable.name'),
                            IconEntry::make('is_completed')
                        ->boolean(),
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
