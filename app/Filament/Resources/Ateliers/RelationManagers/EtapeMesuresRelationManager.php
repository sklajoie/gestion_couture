<?php

namespace App\Filament\Resources\Ateliers\RelationManagers;

use App\Filament\Resources\Ateliers\AtelierResource;
use App\Filament\Resources\EtapeMesures\EtapeMesureResource;
use App\Models\EtapeMesure;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EtapeMesuresRelationManager extends RelationManager
{
    protected static string $relationship = 'etapeMesures';

    // protected static ?string $relatedResource = EtapeMesureResource::class;

    public function table(Table $table): Table
    {
         return EtapeMesureResource::table($table)

            ->headerActions([
                CreateAction::make(),
            ]);
    }

        public function form(Schema $schema): Schema
    {
        
        return EtapeMesureResource::form($schema);
    }

        public function isReadOnly(): bool
    {
        return false;
    }



}
