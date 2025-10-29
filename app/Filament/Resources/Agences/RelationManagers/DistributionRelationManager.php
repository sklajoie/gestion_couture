<?php

namespace App\Filament\Resources\Agences\RelationManagers;

use App\Filament\Resources\DistributionAgences\DistributionAgenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DistributionRelationManager extends RelationManager
{
    protected static string $relationship = 'distribution';

    protected static ?string $relatedResource = DistributionAgenceResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
