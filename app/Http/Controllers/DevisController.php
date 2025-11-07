<?php

namespace App\Http\Controllers;

use App\Models\ClotureCaisse;
use App\Models\Devis;
use App\Models\Entreprise;
use App\Models\Vente;
use App\Models\Versement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
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
         // dd( $encaisse_jour);
        $devis = Devis::where('cloture', $reference)->get();
        $pdf = Pdf::loadView('pdf.cloture_caisse', compact('ventes','devis','cloture',
                                            'encaisse_jour','vente_en_cours','recrouvements'))
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

}
