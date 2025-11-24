<?php

namespace App\Filament\Resources\Ventes\Tables;

use App\Models\User;
use App\Models\Agence;
use App\Models\RetourVente;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\StockAgence;
use Filament\Actions\Action;
use App\Models\StockEntreprise;
use App\Models\Vente;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

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
                
Action::make('retournerProduits')
    ->label('Retourner Produits')
    ->icon('heroicon-m-arrow-uturn-left')
    ->color('info')
    ->modalHeading('Retourner des Produits')
    ->form(function (Vente $record) {
        return [
            DatePicker::make('date_retour')
                ->default(now())
                ->required(),

            Select::make('statut')
                ->options([
                    'Remboursé' => 'Remboursé',
                    'Remplacé' => 'Remplacé',
                    'Retour Atelier' => 'Retour Atelier',
                ])
                ->required(),
           TextInput::make('montant_total')
                    ->label('Montant total du retour')
                    ->default($record->montant_ttc)
                    ->readOnly()
                    ->dehydrated(true), // pour que la valeur soit envoyée à l'action

            Textarea::make('motif'),

            Repeater::make('detailVentes')
                ->schema([
                    Hidden::make('detail_vente_id'),

                    TextInput::make('designation')
                        ->label('Produit')
                        ->disabled(),

                    TextInput::make('quantite')
                        ->numeric()
                        ->minValue(1)
                        ->required(),

                    TextInput::make('prix_unitaire')
                        ->numeric()
                        ->readOnly(),
                    Hidden::make('stock_entreprise_id'),

                    TextInput::make('montant')
                        ->numeric()
                        ->readOnly()
                        ->default(fn ($get) => floatval($get('quantite') ?? 0) * floatval($get('prix_unitaire') ?? 0)),
                ])
                ->columns(5)
                 ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // $state = valeur du repeater
                        $details = $get('detailVentes') ?? [];

                        $total = collect($details)->sum(fn ($item) =>
                            floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0)
                        );

                        $set('montant_total', round($total, 2));
                    })
                ->default(
                    $record->detailVentes->map(fn ($d) => [
                        'detail_vente_id' => $d->id,
                        'designation'     => $d->stockEntreprise->designation,
                        'stock_entreprise_id'     => $d->stock_entreprise_id,
                        'quantite'        => $d->quantite,
                        'prix_unitaire'   => $d->prix_unitaire,
                        'montant'         => $d->quantite * $d->prix_unitaire,
                    ])->toArray()
                ),
        ];
    })
    ->action(function (array $data, Vente $record) {
        $retour = $record->retourVente()->create([
            'date_retour'   => $data['date_retour'],
            'statut'        => $data['statut'],
            'montant_total'        => $data['montant_total'],
            'motif'         => $data['motif'] ?? null,
            'user_id'       => Auth::id(),
            'entreprise_id' => Auth::user()->entreprise_id,
            'agence_id'     => $record->agence_id,
        ]);

        foreach ($data['detailVentes'] as $detail) {
            $retour->detailsRetourVente()->create($detail);
        }
    }),





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

    public static function calculTotaux($state, callable $set, callable $get)
{
    // Total brut = somme des montants des lignes de vente
     $details = $get('detailsRetourVente') ?? [];
    $totalBrut = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
    $set('montant_total', round($totalBrut, 2));
    
}
}
