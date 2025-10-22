<?php

namespace App\Filament\Resources\Employes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                      ->searchable()
                      ->sortable(),
                TextColumn::make('prenom')
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                     ->searchable()
                      ->sortable(),       
                TextColumn::make('adresse')
                    ->label('Adresse')
                     ->searchable()
                      ->sortable(),   
                TextColumn::make('poste')
                    ->label('Poste')
                     ->searchable()
                      ->sortable(),   
                TextColumn::make('date_embauche')
                    ->label('Date d\'embauche')
                     ->date()
                      ->searchable()
                      ->sortable(),
                TextColumn::make('atelier.nom')
                    ->label('Atelier')
                     ->searchable()
                      ->sortable(),
                TextColumn::make('agence.nom')  
                    ->label('Agence')
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
