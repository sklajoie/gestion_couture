<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Atelier;
use App\Models\AutreMesure;
use App\Models\Caisse;
use App\Models\Client;
use App\Models\ClotureAtelier;
use App\Models\ClotureCaisse;
use App\Models\DetailVente;
use App\Models\Devis;
use App\Models\Entreprise;
use App\Models\EtapeMesure;
use App\Models\MesureChemise;
use App\Models\MesureEnsemble;
use App\Models\MesurePantalon;
use App\Models\MesureRobe;
use App\Models\MouvementCaisse;
use App\Models\StockAgence;
use App\Models\StockEntreprise;
use App\Models\Vente;
use App\Models\Versement;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Http\Controllers\Controller;
use App\Models\DetailDevis;

class DevisController extends Controller
{

    public function exportPdf(Devis $devis)
{
    // $pdf = Pdf::loadView('pdf.devis', compact('devis'));
    // return $pdf->download("devis_{$devis->reference}.pdf")
        $pdf = Pdf::loadView('pdf.devis', compact('devis'))
                    ->setPaper('A4')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("devis_{$devis->reference}.pdf");
}

public function imprimerPlusieursDevis(Request $request)
{
    $ids = $request->input('devis_ids', []);
    //dd($ids);
    $devisList = Devis::whereIn('id', $ids)->get();

    $pdf = Pdf::loadView('pdf.devis_multiple', compact('devisList'));
    return $pdf->stream('devis_groupes.pdf');
}

public function imprimerPlusieursVente(Request $request)
{
    $ids = $request->input('vente_ids', []);
    //dd($ids);
    $venteList = Vente::whereIn('id', $ids)->get();

    $pdf = Pdf::loadView('pdf.vente_multiple', compact('venteList'));
    return $pdf->stream('vente_groupes.pdf');
}


    public function impressionvente(Vente $vente)
{
    // $pdf = Pdf::loadView('pdf.devis', compact('devis'));
    // return $pdf->download("devis_{$devis->reference}.pdf")
        $pdf = Pdf::loadView('pdf.vente', compact('vente'))
                    ->setPaper('A4')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("vente_{$vente->reference}.pdf");
}

    public function impressionticketvente(Vente $vente)
{

    //dd($vente);
        $pdf = Pdf::loadView('pdf.vente_ticket', compact('vente'))
                    ->setPaper([0, 0, 250.77, 600], 'portrait')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("vente_{$vente->reference}.pdf");
}


    public function dernierimpressionvente()
{
     $vente = Vente::latest('id')->first();
        $pdf = Pdf::loadView('pdf.vente', compact('vente'))
                    ->setPaper('A4')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("vente_{$vente->reference}.pdf");
}

    public function dernierimpressionticketvente()
{

    //dd($vente);
    $vente = Vente::latest('id')->first();
        $pdf = Pdf::loadView('pdf.vente_ticket', compact('vente'))
                    ->setPaper([0, 0, 250.77, 600], 'portrait')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("vente_{$vente->reference}.pdf");
}


    public function versementticketvente(Request $request)
{

        $ids = $request->input('versement_ids', []);

    $versementList = Versement::whereIn('id', $ids)->get();

    // Regrouper les versements par facture
    $versementsParFacture = $versementList->groupBy('vente_id');   
    // Récupérer les ventes concernées
    $ventes = Vente::whereIn('id', $versementsParFacture->keys())->get()->keyBy('id');
    //dd($vente);
     $entreprise = Entreprise::first();  
        $pdf = Pdf::loadView('pdf.versement_ticket', compact('entreprise','versementsParFacture', 'ventes'))
                    ->setPaper([0, 0, 250.77, 600], 'portrait')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("versement_ticket.pdf");
}

public function imprimerPlusieursVersement(Request $request)
{
    $ids = $request->input('vente_ids', []);
    //dd($ids);
    $versementList = Vente::whereIn('id', $ids)->get();

    $pdf = Pdf::loadView('pdf.versement_multiple', compact('versementList'));
    return $pdf->stream('versement_groupes.pdf');
}


public function imprimerPlusieursVersementFacture(Request $request)
{
    $ids = $request->input('versement_ids', []);

    $versementList = Versement::whereIn('id', $ids)->get();

    // Regrouper les versements par facture
    $versementsParFacture = $versementList->groupBy('vente_id');
     $agence = Vente::where('agence_id', Auth::user()->agence_id)->first();    
    // Récupérer les ventes concernées
    $ventes = Vente::whereIn('id', $versementsParFacture->keys())->get()->keyBy('id');

    $pdf = Pdf::loadView('pdf.versements', compact('versementsParFacture', 'ventes','agence'));
    return $pdf->stream('versement_groupes.pdf');
}


    public function impressioncloturecaisse($reference)
{
        $cloture = ClotureCaisse::where('reference', $reference)->first();
        $ventes = Vente::where('cloture', $reference)
                        ->where('solde', 0)
                        ->get();
        $vente_en_cours = Vente::where('cloture', $reference)
                        ->where('solde','>',0)
                        ->get();
        $mouvementcaisse = MouvementCaisse::where('cloture', $reference)
                           ->get();
        $recrouvements = Versement::where('cloture', $reference)
                        ->where('mode_paiement', "Recouvrement")
                        ->get();
        // $encaisse_jour = Versement::where('cloture', $reference)
        //                 ->select('mode_paiement')
        //                 ->distinct()
        //                 ->get();
        $encaisse_jour = Versement::where('cloture', $reference)
            ->select('mode_paiement', \DB::raw('SUM(montant) as total'))
            ->groupBy('mode_paiement')
            ->get('total', 'mode_paiement'); // retourne une collection [mode => total]

        $montant_encaisse = Versement::where('cloture', $reference)
            ->select('caisse_id', \DB::raw('SUM(montant) as total'))
            ->groupBy('caisse_id')
            ->get('total', 'caisse_id'); // retourne une collection [mode => total]
         // dd( $encaisse_jour);
        $devis = Devis::where('cloture', $reference)->get();
        $pdf = Pdf::loadView('pdf.cloture_caisse', compact('ventes','devis','cloture','mouvementcaisse'
                                           ,'montant_encaisse' ,'encaisse_jour','vente_en_cours','recrouvements'))
                    ->setPaper('A4')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("vente_{$reference}.pdf");
}

    public function impressionclotureatelier($reference)
{
        $cloture = ClotureAtelier::where('reference', $reference)->first();
        // $ventes = ClotureAtelier::where('cloture', $reference)
        //                          ->get();
        $etapeclotures = EtapeMesure::where('cloture', $reference)
                            ->with(['mesureChemise', 'etapeProduction','mesurePantalon','mesureRobe',
                                       'mesureEnsemble','autreMesure','atelier' ])
                            ->get();

        $pdf = Pdf::loadView('pdf.cloture_atelier', compact('etapeclotures','cloture'))
                    ->setPaper('A4','landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("cloture_atelier_{$reference}.pdf");
}

    public function impressionmouvementcaisse($id)
{
    $mouvements = MouvementCaisse::where('id', $id)
                        ->first();
            if($mouvements->structure_type =="AGENCE")        
        $agence = Agence::where('id', $mouvements->structure_id)->first();
        if($mouvements->structure_type =="ATELIER"){
            $agence = Atelier::where('id',$mouvements->structure_id)->first();
        }
        //dd($agence);
        $pdf = Pdf::loadView('pdf.mouvement_caisse', compact('mouvements','agence'))
                    ->setPaper('A4')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);


    return $pdf->stream("mouvement_{$agence->nom}.pdf");
}

public function imprimermouvementcaissegroup(Request $request)
{
    $ids = $request->input('mouvement_ids', []);

    $mouvementliste = MouvementCaisse::whereIn('id', $ids)->get();

    // Regrouper les versements par facture
    $mouvementlistes = $mouvementliste->groupBy('type_mouvement');
     $agence = Entreprise::first();    
    // Récupérer les ventes concernées
    $mouvementgroups = MouvementCaisse::whereIn('type_mouvement', $mouvementlistes->keys())->get()->keyBy('id');
//dd($mouvementgroups);
    $pdf = Pdf::loadView('pdf.mouvement_caisse_group', compact('mouvementgroups','agence'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);
    return $pdf->stream('mouvement_groups.pdf');
}

public function imprimerchemise(Request $request)
{
    $ids = $request->input('mesure_ids', []);

    $chemises = MesureChemise::whereIn('id', $ids)->get();

    // Regrouper les versements par facture
    foreach($chemises as $chemise)
    {

        $etapes = EtapeMesure::where('mesure_chemise_id', $chemise)->get();
    }

     $entreprise = Entreprise::first();    
    // Récupérer les ventes concernées
    $pdf = Pdf::loadView('pdf.imprimer_chemise', compact('etapes','chemises','entreprise'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);;
    return $pdf->stream('mesure_chemise.pdf');
}
public function imprimerpantalon(Request $request)
{
    $ids = $request->input('mesure_ids', []);

    $pantalons = MesurePantalon::whereIn('id', $ids)->get();

     $entreprise = Entreprise::first();    
    // Récupérer les ventes concernées
    $pdf = Pdf::loadView('pdf.imprimer_pantalon', compact('pantalons','entreprise'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);;
    return $pdf->stream('mesure_pantalon.pdf');
}
public function imprimerensemble(Request $request)
{
    $ids = $request->input('mesure_ids', []);

    $ensembles = MesureEnsemble::whereIn('id', $ids)->get();

     $entreprise = Entreprise::first();    
    // Récupérer les ventes concernées
    $pdf = Pdf::loadView('pdf.imprimer_ensemble', compact('ensembles','entreprise'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);;
    return $pdf->stream('mesure_ensemble.pdf');
}
public function imprimerrobe(Request $request)
{
    $ids = $request->input('mesure_ids', []);

    $robes = MesureRobe::whereIn('id', $ids)->get();

     $entreprise = Entreprise::first();    
    // Récupérer les ventes concernées
    
    $pdf = Pdf::loadView('pdf.imprimer_robe', compact('robes','entreprise'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);;
    return $pdf->stream('mesure_robe.pdf');
}

public function imprimerautremesure(Request $request)
{
    $ids = $request->input('mesure_ids', []);

    $autremesures = AutreMesure::whereIn('id', $ids)
                            ->with(['autreMesureDetail', 'user'])
                            ->get();
    //dd($autremesures );
     $entreprise = Entreprise::first();    
    // Récupérer les ventes concernées
    
    $pdf = Pdf::loadView('pdf.imprimer_autremesure', compact('autremesures','entreprise'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);;
    return $pdf->stream('mesure_autre.pdf');
}

public function clotureateliergroup(Request $request)
{
    $ids = $request->input('cloture_ids', []);

    $cloture = ClotureAtelier::whereIn('id', $ids)
                            ->get();

    $etapeclotures = EtapeMesure::whereIn('cloture', $cloture->pluck('reference'))
                            ->with(['mesureChemise', 'etapeProduction','mesurePantalon','mesureRobe',
                                       'mesureEnsemble','autreMesure','atelier','responsable.atelier' ])
                            ->get();
   //dd($etapeclotures );
           
//    $grouped = EtapeMesure::whereIn('id', $etapeclotures->pluck('id'))
//             ->select('employe_id', \DB::raw('SUM(montant) as total'))
//             ->groupBy('employe_id')
//             ->get('total', 'employe_id');
    //dd( $grouped);
            $grouped = $etapeclotures->groupBy('employe_id')->map(function ($items) {
                $first = $items->first();
                return [
                 'responsable'=> $first->responsable,
                    'total' => $items->sum('montant'),
                    'etapeclotures' => $items,
                ];
            });
           //dd($grouped);

     $entreprise = Entreprise::first();    
    // Récupérer les ventes concernées
    
    $pdf = Pdf::loadView('pdf.cloture_atelier_group', compact('etapeclotures','grouped','entreprise'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'enable_php' => true,
                    ])
                    ->setWarnings(false);;
    return $pdf->stream('cloture_atelier_group.pdf');
}


public function espacevente()
{
    // dd(Auth::user()->employe->agence_id);
    $produits = StockAgence::with(['stockEntreprise', 'agence'])->get();
    $caisse = Caisse::where('agence_id', Auth::user()->employe->agence_id)->get();
    $agences = Agence::all();

    return view('page/espace_vente', compact('produits','caisse','agences'));
}

public function recuperationsproduit(Request $request)
{           
 
    // $data = StockAgence::with(['stockEntreprise', 'agence'])
    //                 ->whereRelation('stockEntreprise', 'designation', 'like', '%' . $request->designation . '%')
    //                 ->get();
$data = StockAgence::with(['stockEntreprise', 'agence'])
    ->whereHas('stockEntreprise', function ($query) use ($request) {
        $query->where(function ($subQuery) use ($request) {
            $designation = $request->designation;
            $subQuery->where('designation', 'like', "%$designation%")
                     ->orWhere('reference', 'like', "%$designation%")
                     ->orWhere('code_barre', 'like', "%$designation%");
        });
    })
    ->where('agence_id', Auth::user()->employe->agence_id)
    ->get();




    // $data = StockEntreprise::where('designation', 'like', '%' . $request->designation . '%')->get();

    return response()->json($data);
}


public function savevente(Request $request)
{
           $request->validate([
            'myDataQte' => 'required',
            'ind' => 'required',
           ]);

           //dd($request->submit);
           $currentYear = Carbon::now()->year;
           $currentMonth = Carbon::now()->month;
           $agenceid = Auth::user()->employe->agence_id;
       try {
    $client = Client::where('telephone', $request->numeroclient)->first();
    if (!$client) {
        $client = Client::create([
            'nom' => $request->nomclient,
            'telephone' => $request->numeroclient,
            'user_id' => Auth::user()->id,
        ]);
    }
        $tthorstax = $request->totalht - $request->remise;
        $etat = $request->solde > 0 ? "PAS SOLDEE" : "SOLDEE";

    if($request->submit =="DEVIS")
    {       
            $iddevis = Devis::create([
                    'client_id' => $client->id,
                    'agence_id' => $agenceid,
                    'montant_brut' => $request->totalht,
                    'remise' => $request->remise,
                    'montant_hors_taxe' => $tthorstax,
                    'tva' => $request->tva,
                    'montant_ttc' => $request->totalttc,
                    // 'statut' => $etat,
                    'date_devis' => Carbon::now(),
                    'user_id' => Auth::user()->id,
                ]);

                foreach ($request->ind as $i => $idproduit) {
                    DetailDevis::create([
                        'devis_id' => $iddevis->id,
                        'stock_entreprise_id' => $idproduit,
                        'agence_id' => $agenceid,
                        'quantite' => $request->nqte[$i],
                        'prix_unitaire' => $request->myDataPrix[$i],
                        'montant' => $request->total[$i],
                    ]);
                }
            return back()->with('success', 'Le Devis a été enregistré avec succès');
    }
    elseif($request->submit =="ENREGISTRER")
        {
            
            
       
    $idvent = Vente::create([
        'client_id' => $client->id,
        'agence_id' => $agenceid,
        'montant_brut' => $request->totalht,
        'remise' => $request->remise,
        'montant_hors_taxe' => $tthorstax,
        'tva' => $request->tva,
        'montant_ttc' => $request->totalttc,
        'avance' => $request->avance,
        'solde' => $request->solde,
        'statut' => $etat,
        'date_vente' => Carbon::now(),
        'user_id' => Auth::user()->id,
    ]);

    foreach ($request->ind as $i => $idproduit) {
        DetailVente::create([
            'vente_id' => $idvent->id,
            'stock_entreprise_id' => $idproduit,
            'agence_id' => $agenceid,
            'quantite' => $request->nqte[$i],
            'prix_unitaire' => $request->myDataPrix[$i],
            'montant' => $request->total[$i],
        ]);

        // Optionnel : mise à jour du stock
        // $modif = StockAgence::where('stock_entreprise_id', $idproduit)->first();
        // if ($modif) {
        //     $modif->update(['stock' => $modif->stock - $request->nqte[$i]]);
        // }
    }

    Versement::create([
        'vente_id' => $idvent->id,
        'agence_id' => $agenceid,
        'caisse_id' => $request->caisse,
        'montant' => $request->avance,
        'mode_paiement' => $request->paiement,
        'detail' => $request->detail,
        'user_id' => Auth::user()->id,
    ]);

    return back()->with('success', 'La Vente a été enregistrée avec succès');
    }
} catch (\Exception $e) {
    return back()->with('error', 'Erreur : ' . $e->getMessage());
}


    //dd($request->ind , $request->nqte);
}

public function recuperations()
{

    $tockagence =StockAgence::with(['stockEntreprise', 'agence'])
                            ->where('agence_id', Auth::user()->employe->agence_id)
                            ->get();
//    $entre = \App\Models\StockEntreprise::whereIn('id', $tockagence->pluck('stock_entreprise_id'))->get();
  return $tockagence;
}

}
