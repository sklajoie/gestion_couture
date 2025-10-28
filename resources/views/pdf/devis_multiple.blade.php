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
            position: fixed;
            top: -70px; /* Ajusté pour bien se placer dans la marge du haut */
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
@foreach ($devisList as $devis)
<body>

    <!-- L'en-tête doit être le premier élément du body pour une bonne prise en charge -->
    <header>
        <table class="table1" style="margin-bottom: 25px">
            <tr>
                <td width="33%" style="text-align: left;">
                    @if ($devis->agence->logo)
                        @php
                            $logoPath = public_path("storage/".$devis->agence->logo);
                            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoSrc = "data:image/{$logoType};base64,{$logoData}";
                        @endphp
                        <img src="{{ $logoSrc }}" width="80" alt="Logo de l'entreprise">
                    @else
                        <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('logo/logo.jpg'))) }}" width="80" alt="Logo de l'entreprise">
                    @endif
                    <br><br>
                      <strong>{{ $devis->agence->nom }}</strong><br>
                    {{ $devis->agence->adresse }}<br>
                </td>
                <td width="33%" style="text-align: center;">
                  
                </td>
                <td width="33%" style="text-align: right;">
                    {{ $devis->agence->telephone?? 'N/A' }}<br>
                    {{ $devis->agence->contact?? 'N/A' }}<br>
                    {{ $devis->agence->email?? 'N/A' }} <br><br>
                    {{$devis->agence->ville}} Le: {{ date('d-m-Y') }}
                </td>
            </tr>
        </table>
    </header>
    
    <div class="wrapper" style="margin-top: 40px">
        <section class="invoice">
            <!-- Info client et devis -->
            <table style="margin-bottom: 10px;">
                <tr>
                    <td width="33%">
                        <strong>CLIENT</strong><br>
                        <address>
                            {{ $devis->client->nom ?? 'N/A' }}<br>
                            <b>TELEPHONE:</b> {{ $devis->client->telephone ?? 'N/A' }}<br>
                            <b>ADRESSE:</b> {{ $devis->client->adresse ?? 'N/A' }}<br>
                        </address>
                    </td>
                    <td width="35%">
                        <strong>DEVIS #{{ $devis->reference }}</strong><br>
                        <address>
                        <b>ÉMIS LE: </b>{{ date('d F Y à H\hi', strtotime($devis->date_devis))}}
                        </address>
                    </td>
                    <td width="30%">
                        <b>UTILISATEUR:</b> {{ $devis->user->name ?? 'N/A' }}<br>
                        <b>TELEPHONE:</b> {{ $devis->user->telephone ?? 'N/A' }}<br>
                    </td>
                </tr>
            </table>

            <!-- Table produits -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>PRODUIT</th>
                            <th>QTE</th>
                            <th>PRIX</th>
                            <th>SOUS TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $devis->detailDevis as $devi )
                            <tr>
                                <td>
                                    {{$devi->stockEntreprise->designation}}-
                                    {{$devi->stockEntreprise->couleur->nom}}-
                                    {{$devi->stockEntreprise->taille->nom}}-
                                    {{$devi->stockEntreprise->code_barre}}
                                </td>
                                <td>{{$devi->quantite}}</td>
                                <td>{{$devi->prix_unitaire}}</td>
                                <td>{{$devi->montant}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totaux -->
            <div class="row">
                <div class="col-6"></div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table" style="width:47%;" align="right">
                            <tr>
                                <th>TOTAL BRUT:</th>
                                <td>{{$devis->montant_brut}}</td>
                            </tr>
                            <tr>
                                <th>REMISE</th>
                                <td>{{$devis->remise }}</td>
                            </tr>
                            <tr>
                                <th>MONTANT HORS TAXE</th>
                                <td>{{$devis->montant_hors_taxe }}</td>
                            </tr>
                            <tr>
                                <th>TVA</th>
                                <td>{{$devis->tva }}</td>
                            </tr>
                            <tr>
                                <th>MONTANT TOTAL DEVIS:</th>
                                <td>{{$devis->montant_ttc}}</td>
                            </tr>
                        </table>
                         <p>
                       <i>Arrêté le présent devis à la somme de: </i> :
                        <span style="font-weight:bold;"> {{ strtoupper(NumberHelper::inFrenchWords($devis->montant_ttc)) }}</span>
                    </p>
                     <div align="right" style="font-size:16px;margin-right:50px;font-weight:bold;">
                        <p><u>Signature & Cachet</u></p>
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer>
        <p style="color:#000000 ">{{ $devis->agence->pied_page}}</p>
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
@endforeach
</html>
