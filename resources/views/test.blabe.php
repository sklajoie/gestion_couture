<?php

namespace App\Filament\Resources\MesureChemises\Schemas;

use App\Models\EtapeProduction;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

class MesureChemiseForm
{
    public static function configure(Schema $schema): Schema
    {
   

return $schema
    ->components([
        Wizard::make(array_merge([

            //Étape 1 : Mesure Chemise
            Step::make('Mesure prise')
                ->schema([
                    Section::make('Mesure Chemise')
                        ->columns(2)
                        ->schema([
                            Select::make('Type')
                                ->options([
                                    'ENFANT' => 'ENFANT',
                                    'FEMME' => 'FEMME',
                                    'HOMME' => 'HOMME',
                                ])
                                ->label('Type')
                                ->required(),
                            TextInput::make('Tour_cou')->label('Tour cou')->numeric()->nullable(),
                            TextInput::make('Tour_poitrine')->label('Tour poitrine')->numeric()->nullable(),
                            TextInput::make('Tour_taille')->label('Tour taille')->numeric()->nullable(),
                            TextInput::make('Tour_bassin')->label('Tour bassin')->numeric()->nullable(),
                            TextInput::make('Largeur_epaule')->label('Largeur épaule')->numeric()->nullable(),
                            TextInput::make('Longueur_manche')->label('Longueur manche')->numeric()->nullable(),
                            TextInput::make('Tour_bas')->label('Tour bas')->numeric()->nullable(),
                            TextInput::make('Tour_poignet')->label('Tour poignet')->numeric()->nullable(),
                            TextInput::make('Longueur_chemise')->label('Longueur chemise')->numeric()->nullable(),
                        ]),

                    Section::make('Mesure Chemise')
                        ->columns(2)
                        ->schema([
                            Textarea::make('Description')->label('Description')->nullable(),
                            Hidden::make('user_id')->default(Auth::id()),
                            FileUpload::make('Model_mesure')
                                ->label('Modèle mesure')
                                ->disk('public')
                                ->directory('Model-Mesure')
                                ->visibility('public')
                                ->multiple()
                                ->image()
                                ->imagePreviewHeight('150')
                                ->enableDownload()
                                ->enableOpen(),
                        ]),
                ]),

        ],

        // Étapes dynamiques par étape de production
        
        EtapeProduction::all()->map(function ($etape) {
            return Step::make($etape->nom) // le nom de l’étape comme titre du step
            // ->complete()
                ->schema([
                    Hidden::make("etapes.{$etape->id}.etape_production_id")
                        ->default($etape->id),

                    Textarea::make("etapes.{$etape->id}.comments")
                        ->label('Commentaires')
                        ->nullable(),

                    Toggle::make("etapes.{$etape->id}.is_completed")
                        ->label('Terminée'),

                    Select::make("etapes.{$etape->id}.employe_id")
                        ->label('Responsable')
                        ->options(User::pluck('name', 'id'))
                        ->searchable(),

                    Hidden::make("etapes.{$etape->id}.user_id")
                        ->default(Auth::id()),
                ]);
        })->toArray())

        )->columnSpanFull(),
    ]);

    }
}
