<?php

use App\Http\Controllers\DevisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/devis/{devis}/pdf', [DevisController::class, 'exportPdf'])->name('devis.pdf');
Route::get('/devis/imprimer', [DevisController::class, 'imprimerPlusieursDevis'])->name('devis.imprimer');

Route::get('/impression-vente/{vente}', [DevisController::class, 'impressionvente'])->name('impression-vente');
Route::get('/vente/imprimer', [DevisController::class, 'imprimerPlusieursVente'])->name('vente.imprimer');
Route::get('/vente-ticket/imprimer/{vente}', [DevisController::class, 'impressionticketvente'])->name('vente-ticket.imprimer');
Route::get('/versement/imprimer', [DevisController::class, 'imprimerPlusieursVersement'])->name('versement.imprimer');
Route::get('/versement-facture/imprimer', [DevisController::class, 'imprimerPlusieursVersementFacture'])->name('versement-facture.imprimer');
Route::get('/versement-ticket/imprimer', [DevisController::class, 'versementticketvente'])->name('versement-ticke.imprimer');
Route::get('/cloture-caisse/{reference}', [DevisController::class, 'impressioncloturecaisse'])->name('cloture-caisse');
Route::get('/mouvement-caisse/{id}', [DevisController::class, 'impressionmouvementcaisse'])->name('mouvement-caisse');
Route::get('/imprimer-mouvement-caisse', [DevisController::class, 'imprimermouvementcaissegroup'])->name('imprimer-mouvement-caisse');
