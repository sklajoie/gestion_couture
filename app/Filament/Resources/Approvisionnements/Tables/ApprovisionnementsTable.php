<?php

namespace App\Filament\Resources\Approvisionnements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovisionnementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date_operation', 'desc')
            ->columns([
                TextColumn::make('date_operation')
                    ->sortable()
                    ->label('Date Opération')
                    ->dateTime('j M, Y H:i'),
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('bonCommande.reference')
                    ->label('Bon de commande')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.name')
                    ->label('Opérateur')
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
