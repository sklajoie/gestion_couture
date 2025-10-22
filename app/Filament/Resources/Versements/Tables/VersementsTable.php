<?php

namespace App\Filament\Resources\Versements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VersementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->label('Référence Versement')->searchable()->sortable(),
                TextColumn::make('vente.reference')->label('Référence Vente')->searchable()->sortable(),
                TextColumn::make('montant')->label('Montant')->money('XAF')->sortable(),
                TextColumn::make('mode_paiement')->label('Mode de Paiement')->sortable(),
                TextColumn::make('detail')->label('Détail')->sortable(),
                TextColumn::make('created_at')->label('Date de Création')->sortable(),
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
