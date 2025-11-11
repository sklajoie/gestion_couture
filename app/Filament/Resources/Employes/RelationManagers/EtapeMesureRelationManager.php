<?php

namespace App\Filament\Resources\Employes\RelationManagers;

use App\Filament\Resources\Employes\EmployeResource;
use App\Filament\Resources\EtapeMesures\EtapeMesureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class EtapeMesureRelationManager extends RelationManager
{
    protected static string $relationship = 'etapeMesure';

    // protected static ?string $relatedResource = EmployeResource::class;

    public function table(Table $table): Table
    {
        
        return EtapeMesureResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }

        public function isReadOnly(): bool
    {
        return false;
    }
}
