@php
    use App\Helpers\NumberHelper;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{config('app.name')}}</title>
    
    <style>
        @page {
            /* Crée de l'espace pour l'en-tête et le pied de page */
            margin: 100px 50px 100px 50px;
        }
        
        header {
            /* position: fixed; */
           position: absolute;
            top: -90px; /* Ajusté pour bien se placer dans la marge du haut */
            left: 0px;
            right: 0px;
            height: 70px; /* Ajusté pour correspondre au `top` */
            text-align: center;
            /* background-color: #f2f2f2;
            border-bottom: 1px solid #ccc; */
        }

        footer {
            position: fixed;
            bottom: -70px; /* Ajusté pour bien se placer dans la marge du bas */
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            line-height: 35px;
            border-top: 1px solid #000000;
            /* background-color: #f2f2f2; */
        }
        
        body {
            font-family: sans-serif;
            font-size: 15px;
            margin: 0; /* Important pour que la position fixed soit correcte */
        }
        
        /* Styles pour les tables de l'en-tête */
        .table1 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 52px;
            padding-bottom: 25;
        }

        .table1 td {
            vertical-align: top;
            padding: 5px;
        }

        /* Styles généraux pour le contenu */
        .invoice table {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
        }
        
        .invoice th, .invoice td {
            vertical-align: top;
            padding: 5px;
            border: 1px solid #000000;
        }
        
        .invoice .table-responsive {
            /* margin-top: 20px; */
        }

        .invoice h1 {
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- L'en-tête doit être le premier élément du body pour une bonne prise en charge -->
    <header>
        <table class="table1" style="margin-bottom: 25px">
            <tr>
                <td width="33%" style="text-align: left;">
                    @if ($entreprise->logo)
                        @php
                            $logoPath = public_path("storage/".$entreprise->logo);
                            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoSrc = "data:image/{$logoType};base64,{$logoData}";
                        @endphp
                        <img src="{{ $logoSrc }}" width="80" alt="Logo de l'entreprise">
                    @else
                        <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('logo/logo.jpg'))) }}" width="80" alt="Logo de l'entreprise">
                    @endif
                    <br><br>
                      <strong>{{ $entreprise->nom }}</strong><br>
                    {{ $entreprise->adresse }}<br>
                </td>
                <td width="33%" style="text-align: center;">
                  
                </td>
                <td width="33%" style="text-align: right;">
                    {{ $entreprise->telephone?? 'N/A' }}<br>
                    {{ $entreprise->contact?? 'N/A' }}<br>
                    {{ $entreprise->email?? 'N/A' }} <br><br>
                    {{$entreprise->ville}} Le: {{ date('d-m-Y') }}
                </td>
            </tr>
        </table>
    </header>
    
    <div class="wrapper" style="margin-top: 40px">
        <section class="invoice">
            <!-- Info client et etapeclotures -->
            <!-- Table produits -->
            <div class="table-responsive">
                <p style="text-align: center">CLOTURE DES ETAPES ATELIER</p>
     @php $ttmontant = 0; @endphp


<table class="table table-striped">
    <thead>
        <tr>
            <th>TYPE</th>
            <th>RÉFÉRENCE</th>
            <th>ÉTAPE</th>
            <th>MONTANT</th>
            <th>DATE DÉBUT</th>
            <th>DATE FIN</th>
            <th>TEMPS MIS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($grouped as $employeId => $group)
            <tr class="table-primary fw-bold" style="font-size:16px;margin-right:50px;font-weight:bold;">
                <td colspan="6" >
                    {{ strtoupper($group['responsable']->prenom ?? 'Inconnu') }} {{ strtoupper($group['responsable']->nom ?? '') }}<br>
                    <small>{{ $group['responsable']->atelier->nom ?? 'Atelier inconnu' }}</small>
                </td>
                <td >{{ number_format($group['total'], 0, ',', ' ') }} XOF</td>
            </tr>

            @foreach ($group['etapeclotures'] as $etape)
                @php
                    $ttmontant += $etape->montant;


                    $reference = $etape->mesureChemise?->Reference
                        ?? $etape->mesureRobe?->Reference
                        ?? $etape->mesurePantalon?->Reference
                        ?? $etape->mesureEnsemble?->Reference
                        ?? $etape->autreMesure?->Reference
                        ?? '-';
                @endphp

                <tr>
                    <td>@if ($etape->mesure_chemise_id)
                        CHEMISE 
                        @elseif ($etape->mesure_robe_id)
                        ROBE
                         @elseif ($etape->mesure_pantalon_id )
                         PANTALON 
                         @elseif ($etape->mesure_ensemble_id ) 
                         ENSEMBLE
                         @elseif ($etape->autre_mesure_id ) 
                         AUTRE MESURE
                        @else -
                         @endif</td>
                    <td>{{ $reference }}</td>
                    <td>{{ $etape->etapeProduction->nom }}</td>
                    <td>{{ number_format($etape->montant, 0, ',', ' ') }} XOF</td>
                    <td>{{ \Carbon\Carbon::parse($etape->date_debut)->format('d-m-Y à H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($etape->date_fin)->format('d-m-Y à H:i') }}</td>
                    <td>{{ $etape->temp_mis }}</td>
                </tr>
            @endforeach
        @endforeach

        <tr class="table-dark fw-bold" style="font-size:16px;margin-right:50px;font-weight:bold;">
            <td colspan="6" class="text-end">TOTAL GÉNÉRAL</td>
            <td>{{ number_format($ttmontant, 0, ',', ' ') }} XOF</td>
        </tr>
    </tbody>
</table>



            </div>
           
            <div class="table-responsive" style="font-size:16px;margin-right:50px;font-weight:bold;">
              

                <br>
                <br>
                 <div align="right" style="font-size:16px;margin-right:50px;font-weight:bold;">
                        <p><u>Signature & Cachet</u></p>
                    </div>
            </div>
        </section>
    </div>

    <footer>
        <p style="color:#000000 ">{{$entreprise->pied_page}}</p>
    </footer>
{{-- <script type="text/php">
    if (isset($pdf)) {
        // Obtenir les métriques de police
        $font = $fontMetrics->get_font("helvetica", "bold");
        $size = 10;
        $color = array(0,0,0);
        $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
        
        // Obtenir la largeur de la page et la largeur du texte
        $page_width = $pdf->get_width();
        $text_width = $fontMetrics->get_text_width($text, $font, $size);
        
        // Calculer la position pour centrer le texte
        $x = ($page_width - $text_width) / 2;
        $y = $pdf->get_height() - 50; // Position Y dans le pied de page
        
        // Écrire le texte
        $pdf->page_text($x, $y, $text, $font, $size, $color);
    }
   
</script> --}}

</body>
</html>
