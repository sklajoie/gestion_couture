<?php

namespace App\Filament\Resources\MouvementCaisses\Schemas;

use App\Models\Agence;
use App\Models\Atelier;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MouvementCaisseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference'),
                TextEntry::make('type_mouvement')
                    ->label('Type Mouvement'),
                TextEntry::make('montant'),
                TextEntry::make('mode_reglement')
                    ->label('Mode Règlement'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('mouvementNature.nom')
                    ->label('Nature'),
                TextEntry::make('caisse.nom')
                    ->label('Caisse')
                    ->placeholder('-'),
                TextEntry::make('structure_type')
                    ->placeholder('-')
                    ->label('Type Structure'),
                TextEntry::make('structure_label')
                    ->label('Structure')
                    ->getStateUsing(function ($record) {
                        if ($record->structure_type === 'AGENCE') {
                            $structure = Agence::find($record->structure_id);
                        } elseif ($record->structure_type === 'ATELIER') {
                            $structure = Atelier::find($record->structure_id);
                        } else {
                            return '-';
                        }

                        return $structure ? $structure->nom . ' - ' . $structure->ville : '-';
                    })
                    ->placeholder('-'),
                TextEntry::make('employe.nom')
                    ->label('Employe'),
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
                TextEntry::make('detail')
                    ->label('Detail'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    
}
