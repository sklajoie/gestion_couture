<?php

namespace App\Filament\Resources\StockEntreprises\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockEntreprisesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('designation')->label('Désignation')->searchable()->sortable(),
                TextColumn::make('code_barre')->label('Code barre')->searchable()->sortable(),
                TextColumn::make('reference')->label('Référence')->searchable()->sortable(),
                TextColumn::make('stock')->label('Stock')->sortable(),
                TextColumn::make('prix')->label('Prix')->sortable(),
                TextColumn::make('stock_alerte')->label('Stock alerte')->sortable(),  
                TextColumn::make('couleur.nom')->label('Couleur')->sortable(),
                TextColumn::make('taille.nom')->label('Taille')->sortable(),
                TextColumn::make('user.name')->label('Utilisateur')->sortable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
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
