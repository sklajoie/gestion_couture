<?php

namespace App\Filament\Resources\Agences\RelationManagers;

use App\Filament\Resources\Versements\VersementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VersementRelationManager extends RelationManager
{
    protected static string $relationship = 'versement';

    // protected static ?string $relatedResource = VersementResource::class;

    public function table(Table $table): Table
    {
        // return $table
         return VersementResource::table($table)

            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        
        return VersementResource::form($schema);
    }
      public function isReadOnly(): bool
    {
        return false;
    }
}
