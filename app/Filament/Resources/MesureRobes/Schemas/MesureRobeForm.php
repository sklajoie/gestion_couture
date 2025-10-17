<?php

namespace App\Filament\Resources\MesureRobes\Schemas;

use App\Models\EtapeProduction;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MesureRobeForm
{
    public static function configure(Schema $schema,$record): Schema
    {
           $data = self::getStepsWithStatus($record ?? null);
        return $schema
            ->components([
                  Wizard::make(array_merge([
            //Étape 1 : Mesure Chemise
            Step::make('MESURE ROBE')
                ->schema([
                    Section::make("MESURE D'UNE ROBE")
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
                TextInput::make('Epaule')
                    ->label('Épaule')
                     ->numeric()
                    ->nullable(),
                TextInput::make('Tour_poitrine')
                    ->label('Tour de poitrine')
                     ->numeric()
                    ->nullable(),
                TextInput::make('Tour_taille')
                    ->label('Tour de taille')
                     ->numeric()
                    ->nullable(),
                TextInput::make('Tour_bassin')
                    ->label('Tour de bassin')
                     ->numeric()
                    ->nullable(),
                TextInput::make('Longueur_bassin')
                    ->label('Longueur bassin')
                     ->numeric()
                    ->nullable(),    
                TextInput::make('Longueur_manche')
                    ->label('Longueur manche')
                     ->numeric()
                    ->nullable(),
                TextInput::make('Longueur_manche')
                    ->label('Longueur manche')
                     ->numeric()
                    ->nullable(),
                TextInput::make('Tour_manche')
                    ->label('Tour manche')
                     ->numeric()
                    ->nullable(),
                TextInput::make('Tour_bras') 
                    ->label('Tour bras')
                     ->numeric()
                    ->nullable(),   
                TextInput::make('Carrure_dos') 
                    ->label('Carrure dos')
                     ->numeric()
                    ->nullable(),   
                TextInput::make('Longueur_buste') 
                    ->label('Longueur buste')
                     ->numeric()
                    ->nullable(),   
                TextInput::make('Longueur_robe')
                    ->label('Longueur robe')
                     ->numeric()
                    ->nullable(),
                      ]),
                Section::make('Detail')
                        ->columns(2)
                        ->schema([
                Textarea::make('Description')
                    ->label('Description')
                    ->nullable(),
                FileUpload::make('Model_mesure')
                    ->label('Modèle de mesure')
                    ->disk('public')
                    ->directory('Model-Mesure')
                    ->visibility('public')
                    ->multiple()
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen(),
                Hidden::make('user_id')
                    ->default(Auth::id()),
               ]),
                ]),
        ],
      $data['steps'],))->startOnStep($data['startIndex'])->columnSpanFull(),
    ]);
    }

        public static function getStepsWithStatus(?Model $record): array
{
    $etapes = EtapeProduction::all();
    // $etapeMesures = $record->etapeMesures->keyBy('etape_production_id');
     $etapeMesures = $record?->etapeMesures?->keyBy('etape_production_id') ?? collect();
//dd($etapeMesures);
    $steps = [];
    $startIndex = -1;
     $found = false;
    foreach ($etapes as $i => $etape) {
        $etat = $etapeMesures->get($etape->id);
        $isCompleted = (bool) optional($etat)->is_completed;

        if (! $isCompleted && ! $found) {
            $startIndex = $i + 1; // +1 car "Mesure prise" est en position 0
            $found = true;
        }

        $titre = $etape->nom;

        $steps[] = Step::make($titre)
          ->icon($isCompleted ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
          ->completedIcon($isCompleted ? Heroicon::HandThumbUp :'heroicon-o-clock')
          
            ->schema([
                Hidden::make("etapes.{$etape->id}.etape_production_id")->default($etape->id),
                Textarea::make("etapes.{$etape->id}.comments")->label('Commentaires')->nullable(),
               Toggle::make("etapes.{$etape->id}.is_completed")
                ->label('Terminée'),
                Select::make("etapes.{$etape->id}.responsable_id")
                    ->label('Responsable')
                    ->options(User::pluck('name', 'id'))
                    ->searchable(),
                Hidden::make("etapes.{$etape->id}.user_id")->default(Auth::id()),
            ]);
    }
       return [
    'steps' => $steps,
    'startIndex' => $startIndex,
];
}
}
