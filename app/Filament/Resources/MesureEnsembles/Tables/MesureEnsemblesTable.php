<?php

namespace App\Filament\Resources\MesureEnsembles\Tables;

use App\Models\EtapeProduction;
use App\Models\MesureEnsemble;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MesureEnsemblesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc')
            ->columns([
                //////chemise//////
                TextColumn::make('created_at')
                    ->dateTime('j M, Y H:i')
                    ->searchable()
                    ->label('Date'),
                TextColumn::make('Type')
                    ->label('Type'),
                   
                TextColumn::make('Tour_cou')
                    ->label('Tourm cou'),
                   
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

                //////pantalon//////
                TextColumn::make('Tour_taille_P')
                    ->label('Tour taille'),
                TextColumn::make('Tour_bassin_P')
                    ->label('Tour bassin'),
                TextColumn::make('Tour_cuisse')
                    ->label('Tour cuisse'),   
                TextColumn::make('Tour_genou')
                    ->label('Tour genou'),
                TextColumn::make('Tour_bas_p')
                    ->label('Tour bas'),
                TextColumn::make('Hauteur_genou')
                    ->label('Hauteur genou'),
                TextColumn::make('Hauteur_cheville')
                    ->label('Hauteur cheville'),
                TextColumn::make('Entre_jambe')
                    ->label('Entre-jambe'),
                TextColumn::make('Longueur_pantalon')
                    ->label('Longueur pantalon'),
                // TextColumn::make('Description')
                //     ->label('Description'),   

                // TextColumn::make('user_id')
                //     ->label('Utilisateur'),
                ImageColumn::make('Model_mesure') 
                    ->disk('public')
                    ->label('Modèle')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                   ->options(fn (): array => MesureEnsemble::query()->distinct()->pluck('Type', 'Type')->all())
                   ->multiple()
                   ->label('TYPE COUTURE'),
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
                    ->icon('heroicon-m-pencil-square')
                    ->modalHeading('Transmettre les mesures à une étape')
                   
                    ->form([
                        Select::make('etape_production_id')
                            ->label('Étape de production')
                            ->options(\App\Models\EtapeProduction::pluck('nom', 'id'))
                            ->required(),

                        Select::make('employe_id')
                            ->label('Responsable')
                            ->options(function () {
                            return \App\Models\Employe::all()
                            ->mapWithKeys(fn ($e) => [$e->id => $e->nom . ' ' . $e->prenom])
                            ->toArray(); })
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
                        'employe_id'   => $data['employe_id'],
                        'comments'         => $data['commentaire'],
                        'date_debut'       => $data['date_debut'],
                        'user_id'          => Auth::id(),
                        'is_completed'     => false,
                    ];

                    // Ajouter atelier_id si présent
                    if (!empty($data['atelier_id'])) {
                        $etapeData['atelier_id'] = $data['atelier_id'];

                        \App\Models\EtapeAtelier::create([
                        'employe_id'   => $data['employe_id'],
                        'etape_production_id' => $data['etape_production_id'],
                        'atelier_id' => $data['atelier_id'],
                        'date'       => date(now()), 
                        'user_id'          => Auth::id(),
                        'mesure_type'          => "ENSEMBLE",
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

                        Select::make('employe_id')
                            ->label('Responsable')
                            ->options(function () {
                            return \App\Models\Employe::all()
                            ->mapWithKeys(fn ($e) => [$e->id => $e->nom . ' ' . $e->prenom])
                            ->toArray(); })
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

                     $maxId = \App\Models\EtapeProduction::max('id');

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
                                'employe_id' => $data['employe_id'],
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
                                'employe_id' => $data['employe_id'],
                                'comments' => $data['commentaire'],
                                'date_debut' => Carbon::now(),
                                'date_fin' => Carbon::now(),
                                'user_id' => Auth::id(),
                                'is_completed' => true,
                                'temp_mis' => 0,
                            ]);
                        }

                           // Mise à jour de la mesure
                        if ($etape->etape_production_id == $maxId) {
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
            }),
            /////impression////////
             BulkAction::make('Imprimer Mesure')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('imprimer.ensemble', ['mesure_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                })
            ->deselectRecordsAfterCompletion(),

              DeleteBulkAction::make(),

            ]);
    }
}
