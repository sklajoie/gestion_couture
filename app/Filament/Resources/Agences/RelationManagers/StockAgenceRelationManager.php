<?php

namespace App\Filament\Resources\Agences\RelationManagers;

use App\Filament\Resources\StockAgences\StockAgenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StockAgenceRelationManager extends RelationManager
{
    protected static string $relationship = 'stockAgence';

    // protected static ?string $relatedResource = StockAgenceResource::class;

    public function table(Table $table): Table
    {
        // return $table

        return StockAgenceResource::table($table)

            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        
        return StockAgenceResource::form($schema);
    }
}
