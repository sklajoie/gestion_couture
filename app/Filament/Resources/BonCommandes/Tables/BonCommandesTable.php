<?php

namespace App\Filament\Resources\BonCommandes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BonCommandesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('date_commande')
                    ->date()
                    ->sortable(),
                TextColumn::make('fournisseur.nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_brut')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('remise')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_hors_taxes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tva')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_ttc')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('avance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('solde')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('statut')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->sortable(),
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
                //
            ])
            ->recordActions([
                 ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
