<?php

namespace App\Filament\Resources\MesureChemises\Tables;

// use Filament\Actions\BulkAction;

use App\Models\EtapeAtelier;
use App\Models\EtapeMesure;
use App\Models\EtapeProduction;
use App\Models\MesureChemise;
use Carbon\Carbon;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class MesureChemisesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('created_at')
                    ->dateTime('j M, Y H:i')
                       ->searchable(),

                TextColumn::make('Type')
                    ->label('Type')
                       ->searchable(),
                   
                TextColumn::make('Tour_cou')
                    ->label('Tour cou'),
                   
                TextColumn::make('Tour_poitrine')
                    ->label('Tour poitrine'),
                   
                TextColumn::make('Tour_taille')
                    ->label('Tour taille'),
                   
                TextColumn::make('Tour_bassin')
                    ->label('Tour bassin'),
                   
                TextColumn::make('Largeur_epaule')
                    ->label('Largeur épaule'),
                          
                TextColumn::make('Longueur_manche')
                    ->label('Longueur manche'),
                   
                TextColumn::make('Tour_bas')
                    ->label('Tour bas'),
                   
                TextColumn::make('Tour_poignet')
                    ->label('Tour poignet'),
                TextColumn::make('Longueur_chemise')
                    ->label('Longueur chemise'),

                IconColumn::make('status')
                  ->icon(fn (string $state): Heroicon => match ($state) {
                    '0' => Heroicon::OutlinedPencil,
                    '1' => Heroicon::OutlinedCheckCircle,
                })
                ->boolean(),

              TextColumn::make('dernierEtape.nom')
                ->label('Étape en cours')
                 ->icon(Heroicon::Check)
                  ->badge()
                ->colors([
                    'success' => fn ($state) => $state !== null,
                    'danger' => fn ($state) => $state === null,
                ])
                ->formatStateUsing(fn ($state) => $state ?? 'Terminée'),

            
                ImageColumn::make('Model_mesure') 
                ->disk('public')
                ->label('Modèle'),
            ])
            ->filters([
                Filter::make('status')
                ->query(fn (Builder $query): Builder => $query->where('status', true))
                ->toggle()
                ->label('FINI'),

                SelectFilter::make('etape_id')
                   ->options(fn (): array => EtapeProduction::query()->pluck('nom', 'id')->all())
                   ->multiple()
                   ->label('ETAPE COUTURE'),

                SelectFilter::make('Type')
                   ->options(fn (): array => MesureChemise::query()->distinct()->pluck('Type', 'Type')->all())
                   ->multiple()
                   ->label('TYPE COUTURE'),

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            
            ->toolbarActions([
                
                BulkActionGroup::make([
                    // BulkAction::make('transmettreEtape'),
                    // DeleteBulkAction::make(),
                ]),
               ])
            ->bulkActions([
                BulkAction::make('transmettreEtape')
                    ->label('Assigner Mesure')
                    ->icon('heroicon-m-pencil-square')
                    ->modalHeading('Transmettre les mesures à une étape')
                   
                    ->form([
                        Select::make('etape_production_id')
                            ->label('Étape de production')
                            ->options(\App\Models\EtapeProduction::pluck('nom', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('atelier_id')
                            ->label('Atelier')
                            ->options(\App\Models\Atelier::pluck('nom', 'id'))
                            ->searchable(),

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

                    $etapeData = [
                        'responsable_id'   => $data['responsable_id'],
                        'comments'         => $data['commentaire'],
                        'date_debut'       => $data['date_debut'],
                        'user_id'          => Auth::id(),
                        'is_completed'     => false,
                    ];

                    // Ajouter atelier_id si présent
                    if (!empty($data['atelier_id'])) {
                        $etapeData['atelier_id'] = $data['atelier_id'];

                        EtapeAtelier::create([
                        'responsable_id'   => $data['responsable_id'],
                        'etape_production_id' => $data['etape_production_id'],
                        'atelier_id' => $data['atelier_id'],
                        'date'       => date(now()), 
                        'user_id'          => Auth::id(),
                        'mesure_type'          => "CHEMISE",
                        'mesure_id'          => $mesure->id,
                        ]);              
                    }

                    if ($etape) {
                        $etape->update($etapeData);
                    } else {
                        $mesure->etapeMesures()->create(array_merge(
                            ['etape_production_id' => $data['etape_production_id']],
                            $etapeData
                        ));
                    }
                }


                Notification::make()
                    ->title('Étape assignée avec succès')
                    ->success()
                    ->send();
            }),

             ////////////////valider une etape///////////////
          BulkAction::make('validerEtape')
                    ->label('Valider Une Etape')
                    ->color('success')
                    ->icon('heroicon-m-check')
                    ->modalHeading('Valider une étape de couture')
                   
                    ->form([
                        Select::make('etape_production_id')
                            ->label('Étape de production')
                            ->options(\App\Models\EtapeProduction::pluck('nom', 'id'))
                            ->required(),

                        Select::make('responsable_id')
                            ->label('Responsable')
                            ->options(\App\Models\User::pluck('name', 'id'))
                            ->searchable()
                            ->default(Auth::id())
                            ->required(),
                        DateTimePicker::make('date_fin')
                            ->label('Date Fin')
                            ->default(now())
                            ->required(),
                            
                        Textarea::make('commentaire')
                            ->label('Commentaire')
                            ->rows(3)
                            ->required(),
                    ])
                ->action(function (\Illuminate\Support\Collection $records, array $data) {

                       function isDerniereEtape($etape)
                        {
                            $maxId = \App\Models\EtapeProduction::max('id');
                            return $etape->etape_production_id == $maxId;
                        }

                    foreach ($records as $mesure) {
            $etape = $mesure->etapeMesures()
                ->where('etape_production_id', $data['etape_production_id'])
                ->first();
            $dateDebut = $etape->date_debut? $etape->date_debut : Carbon::now();
                if ($dateDebut && !empty($data['date_fin'])) {
                        $dateDebut = Carbon::parse($etape ->date_debut);
                        $dateFin = Carbon::parse($data['date_fin']);
                        $temp_mis = $dateDebut->diff($dateFin);
                    }


                        if ($etape) {
                            $etape->update([
                                'responsable_id' => $data['responsable_id'],
                                'comments' => $data['commentaire'],
                                'date_debut' => $dateDebut,
                                'date_fin' => $data['date_fin'],
                                'user_id' => Auth::id(),
                                'is_completed' => true,
                                'temp_mis' => $temp_mis,
                            ]);
                        } else {
                            $mesure->etapeMesures()->create([
                                'etape_production_id' => $data['etape_production_id'],
                                'responsable_id' => $data['responsable_id'],
                                'comments' => $data['commentaire'],
                                'date_debut' => Carbon::now(),
                                'date_fin' => Carbon::now(),
                                'user_id' => Auth::id(),
                                'is_completed' => true,
                                'temp_mis' => 0,
                            ]);
                        }
                         // Mise à jour de la mesure
                        if (isDerniereEtape($etape)) {
                            $mesure->update([
                                'etape_id' =>   $data['etape_production_id'],
                                'status' => 1,
                            ]);
                        } else {
                            $mesure->update([
                                'etape_id' =>   $data['etape_production_id'],
                                'status' => 0,
                            ]);
                        }
                    }


                Notification::make()
                    ->title('Étape validée avec succès')
                    ->success()
                    ->send();
            })
            /////valider etape////////

            ->deselectRecordsAfterCompletion(),

              DeleteBulkAction::make(),

            ]);
     
            
        }
        protected function getTableQuery(): Builder
        {
        return MesureChemise::query()->with('dernierEtape');
        }



}
