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
<body>

    <!-- L'en-tête doit être le premier élément du body pour une bonne prise en charge -->
    <header>
        <table class="table1" style="margin-bottom: 25px">
            <tr>
                <td width="33%" style="text-align: left;">
                    @if ($vente->agence->logo)
                        @php
                            $logoPath = public_path("storage/".$vente->agence->logo);
                            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoSrc = "data:image/{$logoType};base64,{$logoData}";
                        @endphp
                        <img src="{{ $logoSrc }}" width="80" alt="Logo de l'entreprise">
                    @else
                        <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('logo/logo.jpg'))) }}" width="80" alt="Logo de l'entreprise">
                    @endif
                    <br><br>
                      <strong>{{ $vente->agence->nom }}</strong><br>
                    {{ $vente->agence->adresse }}<br>
                </td>
                <td width="33%" style="text-align: center;">
                  
                </td>
                <td width="33%" style="text-align: right;">
                    {{ $vente->agence->telephone?? 'N/A' }}<br>
                    {{ $vente->agence->contact?? 'N/A' }}<br>
                    {{ $vente->agence->email?? 'N/A' }} <br><br>
                    {{$vente->agence->ville}} Le: {{ date('d-m-Y') }}
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
                            {{ $vente->client->nom ?? 'N/A' }}<br>
                            <b>TELEPHONE:</b> {{ $vente->client->telephone ?? 'N/A' }}<br>
                            <b>ADRESSE:</b> {{ $vente->client->adresse ?? 'N/A' }}<br>
                        </address>
                    </td>
                    <td width="35%">
                        <strong>VENTE #{{ $vente->reference }}</strong><br>
                        <address>
                        <b>ÉMIS LE: </b>{{ date('d F Y à H\hi', strtotime($vente->date_vente))}}
                        </address>
                    </td>
                    <td width="30%">
                        <b>UTILISATEUR:</b> {{ $vente->user->name ?? 'N/A' }}<br>
                        <b>TELEPHONE:</b> {{ $vente->user->telephone ?? 'N/A' }}<br>
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
                        @foreach ( $vente->detailVentes as $vent )
                            <tr>
                                <td>
                                    {{$vent->stockEntreprise->designation?? 'N/A'}}-
                                    {{$vent->stockEntreprise->couleur->nom?? 'N/A'}}-
                                    {{$vent->stockEntreprise->taille->nom?? 'N/A'}}-
                                    {{$vent->stockEntreprise->code_barre?? 'N/A'}}
                                </td>
                                <td>{{$vent->quantite}}</td>
                                <td>{{$vent->prix_unitaire}}</td>
                                <td>{{$vent->montant}}</td>
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
                                <td>{{$vente->montant_brut}}</td>
                            </tr>
                            <tr>
                                <th>REMISE</th>
                                <td>{{$vente->remise }}</td>
                            </tr>
                            <tr>
                                <th>MONTANT HORS TAXE</th>
                                <td>{{$vente->montant_hors_taxe }}</td>
                            </tr>
                            <tr>
                                <th>TVA</th>
                                <td>{{$vente->tva }}</td>
                            </tr>
                            <tr>
                                <th>MONTANT TOTAL VENTE:</th>
                                <td>{{$vente->montant_ttc}}</td>
                            </tr>
                            <tr>
                                <th>ACOMPTE:</th>
                                <td>{{$vente->avance}}</td>
                            </tr>
                            <tr>
                                <th>RESTE A PAYER:</th>
                                <td>{{$vente->solde}}</td>
                            </tr>
                        </table>
                        <p>
                       <i>Arrêté la présente facture à la somme de: </i> :
                        <span style="font-weight:bold;"> {{ strtoupper(NumberHelper::inFrenchWords($vente->montant_ttc)) }}</span>
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
        <p style="color:#000000 ">{{ $vente->agence->pied_page}}</p>
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
