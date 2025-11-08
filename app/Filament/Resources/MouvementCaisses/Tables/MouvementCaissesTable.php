<?php

namespace App\Filament\Resources\MouvementCaisses\Tables;

use App\Models\Agence;
use App\Models\Atelier;
use App\Models\Caisse;
use App\Models\Employe;
use App\Models\MouvementNature;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Component;

class MouvementCaissesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('type_mouvement')
                    ->searchable()
                    ->label('Type Mouvement'),
                TextColumn::make('mouvementNature.nom')
                    ->label('Nature')
                    ->sortable(),
                TextColumn::make('montant')
                    ->searchable(),
                TextColumn::make('mode_reglement')
                    ->searchable()
                    ->label('Mode Règlement'),
                TextColumn::make('structure_type')
                    ->searchable()
                    ->label('Type Structure'),
                TextColumn::make('structure_id')
                    ->getStateUsing(function ($record) {
                        if ($record->structure_type === 'AGENCE') {
                            $structure = Agence::find($record->structure_id);
                        } elseif ($record->structure_type === 'ATELIER') {
                            $structure = Atelier::find($record->structure_id);
                        } else {
                            return '-';
                        }

                        return $structure ? $structure->nom . ' - ' . $structure->ville : '-';
                    })
                    ->sortable()
                    ->label('Structure'),
                
            
                TextColumn::make('caisse.nom')
                    ->label('Caisse')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('employe.nom')
                    ->label("Employe")
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label("Utilisateur")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cloture')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                   SelectFilter::make('mouvement_nature_id')
                   ->options(fn (): array => MouvementNature::query()->pluck('nom', 'id')->all())
                   ->multiple()
                   ->label('NATURE'),
                   SelectFilter::make('employe_id')
                  ->options(function () {
                        return Employe::orderBy('nom')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => "{$e->prenom} {$e->nom}"])
                            ->toArray();
                    })
                   ->multiple()
                   ->label('EMPLOYE'),
                   SelectFilter::make('caisse_id')
                   ->options(fn (): array => Caisse::query()->pluck('nom', 'id')->all())
                   ->multiple()
                   ->label('Caisse'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('Mouvement Caisse PDF')
                     ->label('Imprimer') // aucun texte affiché
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('mouvement-caisse', ['id' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                
            ])

             ->bulkActions([
                 BulkAction::make('Imprimer Mouvement')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('imprimer-mouvement-caisse', ['mouvement_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                }),

                     DeleteBulkAction::make(),
            ]);
    }
}
