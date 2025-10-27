<?php

use App\Http\Controllers\DevisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/devis/{devis}/pdf', [DevisController::class, 'exportPdf'])->name('devis.pdf');
