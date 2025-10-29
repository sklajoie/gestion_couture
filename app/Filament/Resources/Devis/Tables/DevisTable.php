<?php

namespace App\Filament\Resources\Devis\Tables;

use App\Filament\Resources\Ventes\VenteResource;
use App\Models\DetailVente;
use App\Models\StockAgence;
use App\Models\StockEntreprise;
use App\Models\User;
use App\Models\Vente;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DevisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->label('Référence')->searchable()->sortable(),
                TextColumn::make('client.nom')->label('Client')->searchable()->sortable(),
                // TextColumn::make('agence.nom')->label('Agence')->searchable()->sortable(),
                TextColumn::make('montant_ttc')->label('Montant TTC')->money('XAF', true)->sortable(),
                TextColumn::make('statut')->label('Statut')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_devis')->label('Date Devis')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
               Action::make('voir_facture')
                    ->label('Voir la facture')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => VenteResource::getUrl('view', ['record' => $record->vente_id]))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->vente_id)),
            
                Action::make('Télécharger PDF')
                     ->label('Imprimer') // aucun texte affiché
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('devis.pdf', ['devis' => $record->id]))
                    ->openUrlInNewTab()


            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // BulkAction::make('transformer_devis_facture'),
                    //  BulkAction::make('Imprimer_devis_select'),
                ]),
            ])

            ->bulkActions([
                BulkAction::make('transformer_devis_facture')
                    ->label('Transformer le Devis en Facture')
                    ->icon('heroicon-m-check')
                    ->modalHeading("Vous êtes sur le point de Transformer le devis en facture.")
                    ->color('success')
                    ->modalWidth(Width::Medium)
                    ->action(function (\Illuminate\Support\Collection $records) {
                         $records->load('detailDevis');
                        //  dd($records);
                         DB::transaction(function () use ($records) {
                        foreach ($records as $record) {
                           // dd($record);
                            if ($record->statut) {
                                    continue; // déjà validé, on ignore
                                }
                           $vente = Vente::create([
                                            'client_id' => $record->client_id,
                                            'agence_id' => $record->agence_id,
                                            'montant_brut' =>$record->montant_brut,
                                            'remise' =>$record->remise,
                                            'montant_hors_taxe' => $record->montant_hors_taxe,
                                            'tva' => $record->tva,
                                            'montant_ttc' => $record->montant_ttc,
                                            'solde' => $record->montant_ttc,
                                            'date_vente' => $record->date_devis,
                                            'user_id' => Auth::id(),
                                                ]);
                            foreach ($record->detailDevis as $detail) {
                                $produit = StockAgence::find($detail->stock_entreprise_id);
                               // dd($produit,$detail->stock_entreprise_id);
                               $qte=$detail->quantite;
                                if ($produit && $qte > 0) {
                                     DetailVente::create([
                                            'stock_entreprise_id' => $detail->stock_entreprise_id,
                                            'agence_id' => $record->agence_id,
                                            'quantite' => $qte,
                                            'prix_unitaire' =>$detail->prix_unitaire,
                                            'montant' =>$detail->montant,
                                            'vente_id' => $vente->id,
                                        ]);

                                   $produit->decrement('stock', $qte,);
                                }
                            }

                            //Marquer la distribution comme validée
                            $record->update(['statut' => "FACTURE",'vente_id' => $vente->id,]);
                        }
                        });

                        Notification::make()
                            ->title('Devis Transformé avec succès')
                            ->success()
                            ->send();
                    }),
                //   BulkAction::make('Imprimer sélection')
                //     ->icon('heroicon-o-printer')
                //     ->action(function (Collection $records) {
                //         $ids = $records->pluck('id')->toArray();
                //         return redirect()->route('devis.imprimer', ['devis_ids' => $ids]);
                //     })
                //     ->openUrlInNewTab()
                BulkAction::make('Imprimer sélection')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('devis.imprimer', ['devis_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                })


                    ->deselectRecordsAfterCompletion(),
                        DeleteBulkAction::make(),
                ]);

    }
}
