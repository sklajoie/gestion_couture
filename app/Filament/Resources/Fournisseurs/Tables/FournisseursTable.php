<?php

namespace App\Filament\Resources\Fournisseurs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TextFilter;
use Filament\Tables\Table;

class FournisseursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom du fournisseur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact')
                    ->label('Contact')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('adresse') 
                    ->label('Adresse')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //  TextFilter::make('nom')
                //    ->label('Nom du fournisseur')
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
