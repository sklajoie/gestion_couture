<?php

namespace App\Filament\Resources\Versements\Tables;

use App\Models\Agence;
use App\Models\User;
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

class VersementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Date Versement')->dateTime('d-m-Y H:i')->sortable(),
                TextColumn::make('reference')->label('Référence Versement')->searchable()->sortable(),
                TextColumn::make('vente.reference')->label('Référence Vente')->searchable()->sortable(),
                TextColumn::make('montant')->label('Montant')->money('XAF')->sortable(),
                TextColumn::make('mode_paiement')->label('Mode de Paiement')->sortable(),
                TextColumn::make('agence.nom')->label('Agence')->searchable()->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('detail')->label('Détail')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_id')
                  ->options(function () {
                        return User::orderBy('name')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => "{$e->name}"])
                            ->toArray();
                    })
                   ->multiple()
                   ->label('UTILISATEUR'),
                SelectFilter::make('agence_id')
                   ->options(fn (): array => Agence::query()->pluck('nom', 'id')->all())
                   ->multiple()
                   ->label('AGENCE'),
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
                 BulkAction::make('Imprimer Versement')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('versement-facture.imprimer', ['versement_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                }),
                 BulkAction::make('Imprimer Ticket')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records, Component $livewire) {
                    $ids = $records->pluck('id')->toArray();
                    $url = route('versement-ticke.imprimer', ['versement_ids' => $ids]);
                    
                    $livewire->js('window.open(\'' . $url . '\', \'_blank\');');
                }),

                   DeleteBulkAction::make(),
            ]);
    }
}
