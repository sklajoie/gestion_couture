<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
                    ])
                    ->setWarnings(false);


    return $pdf->stream("devis_{$devis->reference}.pdf");
}
}
