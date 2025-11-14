<?php

namespace App\Filament\Resources\DistributionAgences\Tables;

use App\Models\StockAgence;
use App\Models\StockEntreprise;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DistributionAgencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                        ->dateTime('j M, Y H:i')
                        ->sortable()
                        ->label('Date'),
                TextColumn::make('reference')->label('Référence')->searchable(),
                TextColumn::make('date_operation')->label('Date d\'opération')->searchable(),
                TextColumn::make('user.name')->label('Utilisateur')->searchable(),
                IconColumn::make('is_valide')->label('Est Valide')->boolean(),
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
                
                ]),
            ])

    
          ->bulkActions([
                BulkAction::make('valider_distribution')
                    ->label('Reception Distribution')
                    ->icon('heroicon-m-check')
                    ->modalHeading("Vous êtes sur le point de receptionner la/les distributions sélectionnées")
                    ->color('success')
                    ->modalWidth(Width::Medium)
                    ->action(function (\Illuminate\Support\Collection $records) {
                         $records->load('detailDistributionAgences');
                         
                        foreach ($records as $record) {
                           // dd($record);
                            if ($record->is_valide) {
                                    continue; // déjà validé, on ignore
                                }
                            foreach ($record->detailDistributionAgences as $detail) {
                                $produit = StockEntreprise::find($detail->stock_entreprise_id);
                               // dd($produit,$detail->stock_entreprise_id);
                               $qte=$detail->quantite;
                                if ($qte > 0) {
                                    //dd($produit->stock,$qte);
                                    // Décrémenter le stock entreprise
                                    Log::info("Stock décrémenté ancien stock: {$produit->stock}");
                                   $produit->decrement('stock', $qte);
                                    Log::info("Stock décrémenté, quantité retirée: {$qte}, nouveau stock: {$produit->stock}");
                                    // Mettre à jour ou créer le stock agence
                                      $stockAgence = StockAgence::where('stock_entreprise_id', $detail->stock_entreprise_id)
                                                ->where('agence_id', $record->agence_id)
                                                ->first();
                                            if ($produit && $stockAgence) {
                                                $stockAgence->increment('stock', $qte);
                                            }else{
                                                // Créer un nouvel enregistrement de stock pour l'agence si n'existe pas
                                                StockAgence::create([
                                                    'stock_entreprise_id' => $detail->stock_entreprise_id,
                                                    'agence_id' => $record->agence_id,
                                                    'stock' => $qte,
                                                    'stock_alerte' => 0,
                                                    'user_id' => $record->user_id,
                                                ]);
                                        }

                                }
                            }

                            //Marquer la distribution comme validée
                            $record->update(['validateur_id' => Auth::id(),
                                            'is_valide' => true ]);
                        }

                        Notification::make()
                            ->title('Distributions validées avec succès')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                        DeleteBulkAction::make(),
                ]);

    }

    

    
}
