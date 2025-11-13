<?php

use App\Http\Controllers\DevisController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/devis/{devis}/pdf', [DevisController::class, 'exportPdf'])->name('devis.pdf');
Route::get('/devis/imprimer', [DevisController::class, 'imprimerPlusieursDevis'])->name('devis.imprimer');

Route::get('/impression-vente/{vente}', [DevisController::class, 'impressionvente'])->name('impression-vente');
Route::get('/vente/imprimer', [DevisController::class, 'imprimerPlusieursVente'])->name('vente.imprimer');
Route::get('/vente-ticket/imprimer/{vente}', [DevisController::class, 'impressionticketvente'])->name('vente-ticket.imprimer');
Route::get('/derniere-vente-imprimer', [DevisController::class, 'dernierimpressionvente'])->name('derniere-vente-imprimer');
Route::get('/derniere-vente-ticket-imprimer', [DevisController::class, 'dernierimpressionticketvente'])->name('derniere-vente-ticket-imprimer');
Route::get('/versement/imprimer', [DevisController::class, 'imprimerPlusieursVersement'])->name('versement.imprimer');
Route::get('/versement-facture/imprimer', [DevisController::class, 'imprimerPlusieursVersementFacture'])->name('versement-facture.imprimer');
Route::get('/versement-ticket/imprimer', [DevisController::class, 'versementticketvente'])->name('versement-ticke.imprimer');
Route::get('/cloture-caisse/{reference}', [DevisController::class, 'impressioncloturecaisse'])->name('cloture-caisse');
Route::get('/cloture-atelier/{reference}', [DevisController::class, 'impressionclotureatelier'])->name('cloture-atelier');
Route::get('/cloture-atelier-group', [DevisController::class, 'clotureateliergroup'])->name('cloture-atelier-group');
Route::get('/mouvement-caisse/{id}', [DevisController::class, 'impressionmouvementcaisse'])->name('mouvement-caisse');
Route::get('/imprimer-mouvement-caisse', [DevisController::class, 'imprimermouvementcaissegroup'])->name('imprimer-mouvement-caisse');
Route::get('/imprimer-chemise', [DevisController::class, 'imprimerchemise'])->name('imprimer.chemise');
Route::get('/imprimer-pantalon', [DevisController::class, 'imprimerpantalon'])->name('imprimer.pantalon');
Route::get('/imprimer-ensemble', [DevisController::class, 'imprimerensemble'])->name('imprimer.ensemble');
Route::get('/imprimer-robe', [DevisController::class, 'imprimerrobe'])->name('imprimer.robe');
Route::get('/imprimer-autre-mesure', [DevisController::class, 'imprimerautremesure'])->name('imprimer.autre-mesure');
Route::get('/espace-vente', [DevisController::class, 'espacevente'])->name('espace-vente');
Route::post('/save-vente', [DevisController::class, 'savevente'])->name('save-vente');

Route::match(['get', 'post'], '/recuperations-produit', [DevisController::class, 'recuperationsproduit'])->name('recuperations-produit');
Route::get('/recuperations', function () {
    return \App\Models\StockEntreprise::get();
});