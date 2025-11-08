<?php

namespace App\Filament\Resources\MouvementCaisses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MouvementCaisseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type_mouvement')
                 ->options([
                        'SORTIE DE CAISSE' => 'SORTIE DE CAISSE',
                        'ENTREE EN CAISSE' => 'ENTREE EN CAISSE',
                    ])
                    ->required()
                    ->reactive(),
                Select::make('mouvement_nature_id')
                     ->reactive()
                    ->options(function (callable $get) {
                        $type = $get('type_mouvement');
                        if (! $type) {
                            return [];
                        }
                        return \App\Models\MouvementNature::where('type_mouvement', $type)
                            ->pluck('nom', 'id')
                            ->toArray();
                    })
                    ->label('Nature Mouvement'),


                Select::make('structure_type')
                    ->options([
                            'AGENCE' => 'AGENCE',
                            'ATELIER' => 'ATELIER',
                        ])->reactive()
                        ->required(),
                Select::make('structure_id')
                    ->label('Structure')
                    ->reactive()
                    ->required()
                    ->options(function (callable $get) {
                        $type = $get('structure_type');

                        if ($type === 'AGENCE') {
                            return \App\Models\Agence::get()
                            // return \App\Models\Agence::where('id', Auth::user()->agence_id)
                                ->pluck('nom', 'id')
                                ->toArray();
                        }

                        if ($type === 'ATELIER') {
                            return \App\Models\Atelier::get()
                            // return \App\Models\Atelier::where('id', Auth::user()->atelier_id)
                                ->pluck('nom', 'id')
                                ->toArray();
                        }

                        return [];
                    }),
                Select::make('employe_id')
                    // ->relationship('employe', 'nom')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nom . ' ' . $record->prenom)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->label('Employe')
                     ->options(function (callable $get) {
                        $typeid = $get('structure_id');
                        $type = $get('structure_type');
                         if (! $typeid || ! $type) {
                                return [];
                            }
                        if ($type === 'AGENCE') {
                            return \App\Models\Employe::where('agence_id',  $typeid)
                                ->get()
                                ->mapWithKeys(fn ($e) => [$e->id => $e->nom . ' ' . $e->prenom])
                                ->toArray();
                        }

                        if ($type === 'ATELIER') {
                            return \App\Models\Employe::where('atelier_id', $typeid)
                                ->get()
                                ->mapWithKeys(fn ($e) => [$e->id => $e->nom . ' ' . $e->prenom])
                                ->toArray();
                        }

                        return [];
                    }),
                DatePicker::make('date')
                    ->default(now())
                    ->required(),
              
                Select::make('caisse_id')
                    ->relationship(
                        name: 'caisse',
                        titleAttribute: 'nom',
                        modifyQueryUsing: fn ($query) => $query->where('agence_id', Auth::user()->agence_id)
                                )
                    ->label('Caisse'),

               
                TextInput::make('montant')
                    ->required(),
                Select::make('mode_reglement')
                    ->label('Mode de Paiement')
                    ->options([
                        'Especes' => 'Espèces',
                        'Mobile Money' => 'Mobile Money',
                        'Wave' => 'Wave',
                        'Virement Bancaire' => 'Virement Bancaire',
                        'Cheque' => 'Chèque',
                        'Autre' => 'Autre',
                            'Recouvrement' => 'Recouvrement',
                    ])
                    ->default('Especes'),
                Textarea::make('detail')
                    ->label('Detail Mouvement'),
                Hidden::make('user_id')->default(Auth::id()),
            ]);
    }
}
