<?php

namespace App\Filament\Resources\ApprovisionnementStocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovisionnementStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->label('Référence'),
                TextColumn::make('date_operation')
                    ->label('Date Opération')
                    ->dateTime('d/m/Y H:i'),
                TextColumn::make('total_appro')
                    ->label('Montant Total')
                    ->money('XOF', true),
                TextColumn::make('user.name')
                    ->label('Utilisateur'),
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
