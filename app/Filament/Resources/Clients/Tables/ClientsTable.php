<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telephone')
                     ->label('Téléphone')
                     ->searchable()
                      ->sortable(),
                TextColumn::make('email')
                     ->label('Email')
                     ->searchable()
                      ->sortable(),  
                      TextColumn::make('ville')
                     ->label('Ville')
                     ->searchable()
                      ->sortable(),      
                TextColumn::make('adresse')
                     ->label('Adresse')
                     ->searchable()
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
