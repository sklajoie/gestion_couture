<?php

namespace App\Filament\Resources\EtapeMesures\Tables;

use App\Models\EtapeMesure;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EtapeMesuresTable
{
    public static function configure(Table $table): Table
    {
        
       
        return $table
        //  ->reorderable('sort', id: 'desc')
         ->striped()
         ->searchable()
         ->deferLoading()
             ->columns([
            TextColumn::make('etapeProduction.nom')->label('Étape')
              ->searchable()
              ->sortable(),
            TextColumn::make('responsable.name')->label('Responsable')
            ->searchable()
              ->sortable(),
            ToggleColumn::make('is_completed')
                ->label('Statut')
                ->sortable()
                ->disabled(fn ($record) => $record->responsable_id !== Auth::id() || $record->is_completed )
                ->afterStateUpdated(function ($state, $record) {
                     $maxId = \App\Models\EtapeProduction::max('id');
                    // Ce bloc ne sera exécuté que si l'utilisateur est le responsable
                    $record->is_completed = $state;
                    $record->date_fin = now();
                    $record->temp_mis = Carbon::parse($record->date_debut)->diff(now());
                    $record->save();
                    // Si c'est la dernière étape de production
                    $mesure = $record->mesureChemise
                    ?? $record->mesurePantalon
                    ?? $record->mesureRobe
                    ?? $record->mesureEnsemble
                    ?? $record->autreMesure;
                    
                    if ($mesure) {
                            if ($record->etape_production_id == $maxId) {
                            $mesure->update([
                                'etape_id' => $record->etape_production_id,
                                'status' => 1,
                            ]);
                        }else {
                            $mesure->update([
                                'etape_id' =>   $record->etape_production_id,
                                'status' => false,
                            ]);
                        }
                        }
                    Notification::make()
                        ->title('Étape mise à jour avec succès')
                        ->success()
                        ->send();
                            
                            }),
            TextColumn::make('comments')->label('Commentaires')->limit(50),
            TextColumn::make('mesure_liee') // n'importe quel nom qui n'entre pas en conflit
                ->label('Mesure liée')
                ->getStateUsing(function ($record) {
                    if ($record->mesureChemise) {
                        return "Chemise #{$record->mesureChemise->Reference}";
                    } elseif ($record->mesurePantalon) {
                        return "Pantalon #{$record->mesurePantalon->Reference}";
                    } elseif ($record->mesureRobe) {
                        return "Robe #{$record->mesureRobe->Reference}";
                    } elseif ($record->mesureEnsemble) {
                        return "Ensemble #{$record->mesureEnsemble->Reference}";
                    } elseif ($record->autreMesure) {
                        return "Autre #{$record->autreMesure->Reference}";
                    }

                    return '—';
                })
                  ->url(function ($record) {
                    if ($record->mesureChemise) {
                        return route('filament.admin.resources.mesure-chemises.view', ['record' => $record->mesureChemise->id]);
                    } elseif ($record->mesurePantalon) {
                        return route('filament.admin.resources.mesure-pantalons.view', ['record' => $record->mesurePantalon->id]);
                    } elseif ($record->mesureRobe) {
                        return route('filament.admin.resources.mesure-robes.view', ['record' => $record->mesureRobe->id]);
                    } elseif ($record->mesureEnsemble) {
                        return route('filament.admin.resources.mesure-ensembles.view', ['record' => $record->mesureEnsemble->id]);
                    } elseif ($record->autreMesure) {
                        return route('filament.admin.resources.autre-mesures.view', ['record' => $record->autreMesure->id]);
                    }

                    return null;
                })
                ->openUrlInNewTab()



        ])
            ->filters([
                SelectFilter::make('responsable_id')
                ->label('Filtrer par responsable')
                 ->multiple()
                ->options(User::pluck('name', 'id')),
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
                BulkAction::make("Valider les Etapes")
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                       ->action(function ($records) {
                            $validatedCount = 0;
                            $skippedCount = 0;
                        $maxId = \App\Models\EtapeProduction::max('id');
                            foreach ($records as $record) {
                                if ($record->responsable_id === Auth::id() && ! $record->is_completed) {
                                    $record->is_completed = true;
                                    $record->date_fin = now();
                                    $record->temp_mis = Carbon::parse($record->date_debut)->diff(now());
                                    $record->save();
                                    $validatedCount++;

                                   $mesure = $record->mesureChemise
                                            ?? $record->mesurePantalon
                                            ?? $record->mesureRobe
                                            ?? $record->mesureEnsemble
                                            ?? $record->autreMesure;
                                            
                                            if ($mesure) {
                                                    if ($record->etape_production_id == $maxId) {
                                                    $mesure->update([
                                                        'etape_id' => $record->etape_production_id,
                                                        'status' => 1,
                                                    ]);
                                                }else {
                                                    $mesure->update([
                                                        'etape_id' =>   $record->etape_production_id,
                                                        'status' => false,
                                                    ]);
                                                }
                                                }
                                } else {
                                    $skippedCount++;
                                }

                            
                            }

                            if ($validatedCount > 0) {
                                Notification::make()
                                    ->title("{$validatedCount} étape(s) validée(s) avec succès")
                                    ->success()
                                    ->send();
                            }

                            if ($skippedCount > 0) {
                                Notification::make()
                                    ->title("{$skippedCount} étape(s) ignorée(s) — vous n’êtes pas le responsable")
                                    ->warning()
                                    ->send();
                            }
                        })
                        
            ->deselectRecordsAfterCompletion(),

              DeleteBulkAction::make(),
            ]);
    }
}
