<?php

namespace App\Filament\Resources\Ventes\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Component;

class VentesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->label('Référence')->searchable()->sortable(),
                TextColumn::make('client.nom')->label('Client')->searchable()->sortable(),
                TextColumn::make('agence.nom')->label('Agence')->searchable()->sortable(),
                TextColumn::make('montant_ttc')->label('Montant TTC')->money('XAF', true)->sortable(),
                TextColumn::make('avance')->label('Avance')->money('XAF', true)->sortable(),
                TextColumn::make('solde')->label('Solde')->money('XAF', true)->sortable(),
                TextColumn::make('statut')->label('Statut')->sortable(),
                TextColumn::make('date_vente')->label('Date de Vente')->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('Facture PDF')
                     ->label('Imprimer') // aucun texte affiché
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('impression-vente', ['vente' => $record->id]))
                    ->openUrlInNewTab(),
                    
                Action::make('Ticket Facture PDF')
                     ->label('Ticket') // aucun texte affiché
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('vente-ticket.imprimer', ['vente' => $record->id]))
                    ->openUrlInNewTab(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ])

            ->bulkActions([
                  BulkAction::make('Imprimer Facture')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('vente.imprimer', ['vente_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                }),
                  BulkAction::make('Imprimer Facture avec Versement')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('versement.imprimer', ['vente_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                }),
            ]);
    }
}
