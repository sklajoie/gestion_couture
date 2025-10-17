<?php

namespace App\Filament\Resources\MesureRobes\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MesureRobesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Type')
                    ->label('Type'),
                TextColumn::make('Epaule')
                    ->label('Épaule'),
                TextColumn::make('Tour_poitrine')
                    ->label('Tour poitrine'),
                TextColumn::make('Tour_taille')
                    ->label('Tour taille'),
                TextColumn::make('Tour_bassin')
                    ->label('Tour bassin'),
                TextColumn::make('Longueur_bassin')
                    ->label('Longueur bassin'),    
                TextColumn::make('Carrure_dos')
                    ->label('Carrure dos'),    
                TextColumn::make('Longueur_buste')
                    ->label('Longueur buste'),    
                TextColumn::make('Longueur_manche')
                    ->label('Longueur manche'),
                TextColumn::make('Tour_manche')
                    ->label('Tour manche'),
                TextColumn::make('Tour_bras') 
                    ->label('Tour bras'),   
                TextColumn::make('Longueur_robe')
                    ->label('Longueur robe'),
                // TextColumn::make('Description')
                //     ->label('Description'),
                // TextColumn::make('user.name')
                //     ->label('Utilisateur'),
                ImageColumn::make('Model_mesure') 
                ->disk('public')
                ->label('Modèle'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ])

            ->bulkActions([
                BulkAction::make('transmettreEtape')
                    ->label('Assigner Mesure')
                    ->modalHeading('Transmettre les mesures à une étape')
                   
                    ->form([
                        Select::make('etape_production_id')
                            ->label('Étape de production')
                            ->options(\App\Models\EtapeProduction::pluck('nom', 'id'))
                            ->required(),

                        Select::make('responsable_id')
                            ->label('Responsable')
                            ->options(\App\Models\User::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        DateTimePicker::make('date_debut')
                            ->label('Date de début')
                            ->default(now())
                            ->nullable(),
                            
                        Textarea::make('commentaire')
                            ->label('Commentaire')
                            ->rows(3)
                            ->nullable(),
                    ])
                    ->action(function (\Illuminate\Support\Collection $records, array $data) {
                       foreach ($records as $mesure) {
                            $etape = $mesure->etapeMesures()
                                ->where('etape_production_id', $data['etape_production_id'])
                                ->first();

                            if ($etape) {
                                $etape->update([
                                    'responsable_id' => $data['responsable_id'],
                                    'comments' => $data['commentaire'],
                                    'date_debut' => $data['date_debut'],
                                    'user_id' => Auth::id(),
                                    'is_completed' => false,
                                ]);
                            } else {
                                $mesure->etapeMesures()->create([
                                    'etape_production_id' => $data['etape_production_id'],
                                    'responsable_id' => $data['responsable_id'],
                                    'comments' => $data['commentaire'],
                                    'date_debut' => $data['date_debut'],
                                    'user_id' => Auth::id(),
                                    'is_completed' => false,
                                ]);
                            }
                        }


                Notification::make()
                    ->title('Étape assignée avec succès')
                    ->success()
                    ->send();
            })
            ->deselectRecordsAfterCompletion(),

              DeleteBulkAction::make(),

            ]);
    }
}
