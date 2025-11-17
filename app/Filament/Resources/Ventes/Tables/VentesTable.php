<?php

namespace App\Filament\Resources\Ventes\Tables;

use App\Models\Agence;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

class VentesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->defaultSort('date_vente', 'desc')
            ->columns([
                TextColumn::make('date_vente')->label('Date de Vente')->dateTime('d-m-Y H:i')->sortable(),
                TextColumn::make('reference')->label('Référence')->searchable()->sortable(),
                TextColumn::make('client.nom')->label('Client')->searchable()->sortable(),
                TextColumn::make('agence.nom')->label('Agence')->searchable()->sortable(),
                TextColumn::make('montant_ttc')->label('Montant TTC')->money('XAF', true)->sortable(),
                TextColumn::make('avance')->label('Avance')->money('XAF', true)->sortable(),
                TextColumn::make('solde')->label('Solde')->money('XAF', true)->sortable(),
                TextColumn::make('statut')->label('Statut')->sortable()
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'SOLDEE' => 'success',
                    'PAS SOLDEE' => 'danger',
                }),
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

                  Filter::make('date_vente')
                ->schema([
                    DatePicker::make('created_from')->label('Debut'),
                    DatePicker::make('created_until')->label('Fin'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date_vente', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date_vente', '<=', $date),
                        );
                })
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
                 DeleteBulkAction::make(),
            ]);
    }
}
