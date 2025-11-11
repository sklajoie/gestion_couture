<?php

namespace App\Filament\Resources\EtapeMesures\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EtapeMesureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                
        Select::make('mesure_chemise_id')
            ->label('Mesure à transmettre')
            ->relationship('mesureChemise', 'reference') // ou autre champ lisible
            ->searchable()
            ->required(),

        Select::make('etape_production_id')
            ->label('Étape de production')
            ->relationship('etapeProduction', 'nom')
            ->searchable()
            ->required(),

        Select::make('employe_id')
            ->label('Responsable')
            ->options(function () {
            return \App\Models\Employe::all()
            ->mapWithKeys(fn ($e) => [$e->id => $e->nom . ' ' . $e->prenom])
            ->toArray(); })
            ->searchable()
            ->required(),

        Checkbox::make('is_completed')
            ->label('Étape terminée'),

        Textarea::make('comments')
            ->label('Commentaires'),
                    ]);
    }
}
