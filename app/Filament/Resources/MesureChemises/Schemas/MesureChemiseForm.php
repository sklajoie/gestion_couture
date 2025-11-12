<?php

namespace App\Filament\Resources\MesureChemises\Schemas;

use App\Models\Couleur;
use App\Models\Employe;
use App\Models\EtapeProduction;
use App\Models\Produit;
use App\Models\Taille;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
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
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class MesureChemiseForm
{
    public static function configure(Schema $schema,$record): Schema
    {
   

        $data = self::getStepsWithStatus($record ?? null);
return $schema
    ->components([
        Wizard::make(array_merge([
            //Étape 1 : Mesure Chemise
            Step::make('MESURE CHEMISE')
                ->schema([
                    Section::make("MESURE D'UNE CHEMISE")
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

                    Section::make('Detail')
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
                                ->enableOpen()
                                ->openable()
                                 ->previewable()
                                 ->reorderable()
                                 ->appendFiles()
                                ->panelLayout('grid'),
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
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $produit = \App\Models\Produit::find($state);
                                            $prix = $produit?->prix_vente ?? 0;
                                            $set('prix_unitaire', $prix);

                                            // Optionnel : recalcul du total si quantité déjà définie
                                            $qte = floatval($produit?->quantite ?? 0);
                                            $set('total', $qte * $prix);
                                            self::calcTotals($state, $set, $get);
                                        }),
                                    TextInput::make('prix_unitaire')
                                        ->label('Prix unitaire')
                                        ->numeric()
                                        ->readOnly()
                                        ->reactive()
                                        ->live()
                                        ->readOnly(),

                                    TextInput::make('quantite')
                                        ->label('Quantité')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(1)
                                        ->required()
                                        ->reactive()
                                         ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                           
                                            $prix = floatval($get('prix_unitaire') ?? 0);
                                            $set('total', $state * $prix);
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
                                ->default([]) // ← important pour éviter les erreurs si vide
                                ->dehydrated(true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                    $details = $get('produitCouture') ?? [];
                                    $totalProduit = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
                                    $set('total_produit', round($totalProduit, 2));

                                 self::calcTotals($state, $set, $get);
                                }),
                                TextInput::make('total_produit')
                                        ->label('TOTAL PRODUIT')
                                        ->numeric()
                                         ->live()
                                         ->reactive()
                                        ->readOnly(),
                                TextInput::make('main_oeuvre')
                                        ->label('Main d\'œuvre')
                                        ->numeric()
                                        ->nullable()
                                         ->reactive()
                                         ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                 self::calcTotals($state, $set, $get);
                                }),
                                TextInput::make('prix_couture')
                                        ->label('Prix Couture')
                                        ->numeric()
                                        ->readOnly()
                                        ->nullable()
                                         ->reactive()
                                         ->live(),
                                TextInput::make('prix_vente')
                                        ->label('Prix Vente')
                                        ->numeric()
                                        ->nullable(),
                                Select::make('couleur_id')
                                        ->options(Couleur::query()->pluck('nom', 'id'))
                                        ->searchable()
                                        ->label('Couleur'),
                                Select::make('taille_id')
                                        ->options(Taille::query()->pluck('nom', 'id'))
                                        ->searchable()
                                        ->label('Taille'),
                                
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
               Select::make("etapes.{$etape->id}.employe_id")
                    ->options(function () {
                        return \App\Models\Employe::all()
                        ->mapWithKeys(fn ($e) => [$e->id => $e->nom . ' ' . $e->prenom])
                        ->toArray();
                    })
                    //->getOptionLabelFromRecordUsing(fn ($record) => "{$record->prenom} {$record->nom}")
                    ->searchable()
                    ->preload(),
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
                TextInput::make("etapes.{$etape->id}.montant")
                    ->label('Montant')
                    ->numeric()
                    ->default(0)
                     ->reactive()
                    ->live(onBlur: true)
                    ->dehydrated(true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                              $etapes = $get('etapes') ?? [];
                            $totalmainoeuvre = collect($etapes)->sum(fn ($item) =>
                                    floatval($item['montant'] ?? 0)
                                );
                                $set('main_oeuvre', round( $totalmainoeuvre, 2));
                            // dd( $details);
                        self::calcTotals($state, $set, $get);
                    }),
                // TextInput::make("etapes.{$etape->id}.atelier_id")
                //     ->label('Atelier')
                //     ->readOnly()
                //     ->nullable(),
                Select::make("etapes.{$etape->id}.atelier_id")
                    ->label('Atelier')
                   ->options(\App\Models\Atelier::pluck('nom', 'id')),
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

    $details = $get('produitCouture') ?? [];
    $totalProduit = collect($details)->sum(fn ($item) => floatval($item['quantite'] ?? 0) * floatval($item['prix_unitaire'] ?? 0));
     $etapes = $get('etapes') ?? [];
   $totalmainoeuvre = collect($etapes)->sum(fn ($item) =>
        floatval($item['montant'] ?? 0)
    );
    $set('main_oeuvre', round( $totalmainoeuvre, 2));
   // dd( $details);

    $prixCouture = $totalProduit + $totalmainoeuvre;
    $set('total_produit', round( $totalProduit, 2));
    $set('prix_couture', round( $prixCouture, 2));
    }


}
