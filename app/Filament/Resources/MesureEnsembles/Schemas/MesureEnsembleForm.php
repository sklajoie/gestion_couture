<?php

namespace App\Filament\Resources\MesureEnsembles\Schemas;

use App\Models\EtapeProduction;
use App\Models\Produit;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
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

class MesureEnsembleForm
{
    public static function configure(Schema $schema,$record): Schema
    {
           $data = self::getStepsWithStatus($record ?? null);
        return $schema
            ->components([
            Wizard::make(array_merge([
            //Étape 1 : Mesure Chemise
            Step::make('MESSURE ENSEMBLE')
                ->schema([
                Section::make("MESURE D'UN ENSEMBLE")
                        ->columns(2)
                        ->schema([
                //////chemise//////
                    Section::make('MESURE CHEMISE')
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
                        TextInput::make('Tour_cou')
                            ->label('Tour cou')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_poitrine')
                            ->label('Tour poitrine')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_taille')
                            ->label('Tour taille')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_bassin')
                            ->label('Tour bassin')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Largeur_epaule')
                            ->label('Largeur épaule')
                            ->numeric()
                            ->nullable(),       
                        TextInput::make('Longueur_manche')
                            ->label('Longueur manche')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_bas')
                            ->label('Tour bas')  
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_poignet')
                            ->label('Tour poignet')
                            ->numeric(),
                        TextInput::make('Longueur_chemise')
                            ->label('Longueur chemise')
                            ->numeric()
                            ->nullable(),
                        ]),
                        //////pantalon//////
                    Section::make('MESURE PANTALON')
                            ->columns(2)
                            ->schema([
                        TextInput::make('Tour_taille_P')
                            ->label('Tour taille')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_bassin_P')
                            ->label('Tour bassin')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_cuisse')
                            ->label('Tour cuisse')
                            ->numeric()
                            ->nullable(),   
                        TextInput::make('Tour_genou')
                            ->label('Tour genou')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Tour_bas_p')
                            ->label('Tour bas')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Hauteur_genou')
                            ->label('Hauteur genou')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Hauteur_cheville')
                            ->label('Hauteur cheville')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Entre_jambe')
                            ->label('Entre-jambe')
                            ->numeric()
                            ->nullable(),
                        TextInput::make('Longueur_pantalon')
                            ->label('Longueur pantalon')
                            ->numeric()
                            ->nullable(),
                        ]), 
                    ]),
                Section::make('Detail')
                        ->columns(2)
                        ->schema([
                    Textarea::make('Description')
                        ->label('Description')
                        ->nullable(), 
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

                    Hidden::make('user_id')
                        ->default(Auth::id()),
                ////////////choix produit
                    Repeater::make('produitCouture')
                        ->label('Produits commandés')
                        ->relationship('produitCouture') // Assure-toi que la relation existe dans le modèle BonCommande
                        ->schema([
                            Select::make('produit_id')
                                ->label('Produit')
                                ->relationship('produit', 'nom')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->getOptionLabelFromRecordUsing(fn (Produit $record) => "{$record->nom} ({$record->code_barre})")
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $produit = \App\Models\Produit::find($state);
                                    $prix = $produit?->prix_vente ?? 0;
                                    $set('prix_unitaire', $prix);

                                    // Optionnel : recalcul du total si quantité déjà définie
                                    $qte = floatval($produit?->quantite ?? 1);
                                    $set('total', $qte * $prix);
                                    self::calcTotals($state, $set, $get);
                                }),
                            TextInput::make('prix_unitaire')
                                ->label('Prix unitaire')
                                ->numeric()
                                ->readOnly()
                                ->reactive()
                                ->readOnly(),

                            TextInput::make('quantite')
                                ->label('Quantité')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $qte = floatval($state ?? 0);
                                    $prix = floatval($get('prix_unitaire') ?? 0);
                                    $set('total', $qte * $prix);
                                    self::calcTotals($state, $set, $get);
                                }),

                            TextInput::make('total')
                                ->label('Total')
                                ->numeric()
                                ->default(0.0)
                                ->readOnly()
                                ->reactive(),
                                        
                                ])
                                ->columns(4) // Affiche les 4 champs sur une seule ligne
                                ->createItemButtonLabel('Ajouter un produit')
                                ->columnSpanFull()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                 self::calcTotals($state, $set, $get);
                                }),
                                TextInput::make('total_produit')
                                        ->label('TOTAL PRODUIT')
                                        ->numeric()
                                        ->readOnly(),
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

        // $titre = $etape->nom . ($isCompleted ? ' ✅' : ' ⏳');
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
                DateTimePicker::make("etapes.{$etape->id}.date_debut")
                    ->label('Date de début')
                    ->nullable(),

                DateTimePicker::make("etapes.{$etape->id}.date_fin")
                    ->label('Date de fin')
                    ->readOnly()
                    ->nullable(),
                TextInput::make("etapes.{$etape->id}.temp_mis")
                    ->label('Temps mis')
                    ->readOnly()
                    ->nullable(),
                Hidden::make("etapes.{$etape->id}.user_id")->default(Auth::id()),
            ]);
    }
       return [
    'steps' => $steps,
    'startIndex' => $startIndex,
];
}

    public static function calcTotals( $state,callable $set, callable $get)
    {
        $details = $get('produitCouture') ;

        $totalProduit = collect($details)
            ->pluck('total')
            ->filter(fn($v) => is_numeric($v))
            ->map(fn($v) => floatval($v))
            ->sum();
        $set('total_produit', round( $totalProduit, 2));
    }
}
