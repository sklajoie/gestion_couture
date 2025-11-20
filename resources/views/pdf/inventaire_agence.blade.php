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
                    @if ($agence->logo)
                        @php
                            $logoPath = public_path("storage/".$agence->logo);
                            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoSrc = "data:image/{$logoType};base64,{$logoData}";
                        @endphp
                        <img src="{{ $logoSrc }}" width="80" alt="Logo de l'entreprise">
                    @else
                        <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('logo/logo.jpg'))) }}" width="80" alt="Logo de l'entreprise">
                    @endif
                    <br><br>
                      <strong>{{ $agence->nom?? 'N/A' }}</strong><br>
                    {{ $agence->adresse ?? 'N/A'}}<br>
                </td>
                <td width="33%" style="text-align: center;">
                  
                </td>
                <td width="33%" style="text-align: right;">
                    {{ $agence->telephone?? 'N/A' }}<br>
                    {{ $agence->contact?? 'N/A' }}<br>
                    {{ $agence->email?? 'N/A' }} <br><br>
                    {{$agence->ville}} Le: {{ date('d-m-Y') }}
                </td>
            </tr>
        </table>
    </header>
    
    <div class="wrapper" style="margin-top: 40px">
        <section class="invoice">
            <!-- Info client et devis -->
            <table style="margin-bottom: 10px;">
                <tr>
                     <td width="60%">
                        <b>UTILISATEUR:</b> {{ Auth::user()->name ?? 'N/A' }}<br>
                        <b>TELEPHONE:</b> {{ Auth::user()->telephone ?? 'N/A' }}<br>
                    </td>
                    <td width="40%">
                       
                        <address>
                        <b>DU:</b>{{ date('d F Y', strtotime($dateDebut)) }}
                        <b>AU:</b> {{ date('d F Y', strtotime($dateFin)) }} <br>
                        </address>
                    </td>
                   
                </tr>
            </table>

            <!-- Table produits -->
            <div class="table-responsive">
                <p style="text-align: center">ETAT INVENTAIRE </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>REFERENCE</th>
                            <th>NOM</th>
                            <th>STOCK</th>
                            <th>DISTRIBUE</th>
                            <th>VENDUE</th>
                            <th>PRIX ACHAT</th>
                            <th>PRIX VENTE</th>
                            <th>BENEFICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $ttbenefice = 0; @endphp
                        @foreach ($etatProduits as $produitId => $etat )
                        @php $produit = \App\Models\StockEntreprise::find($produitId) ;
                        $ttbenefice += ($produit->prix - $produit->prix_achat) * $etat['vendu'];
                        @endphp
                        
                            <tr>
                                <td>{{ $produit->reference}}</td>
                                <td>{{ $produit->designation }}</td>
                                <td>{{$etat['stock'] }}</td>
                                <td>{{$etat['distribue']}}</td>
                                <td>{{$etat['vendu'] }}</td>
                                <td>{{$produit->prix_achat }}</td>
                                <td>{{$produit->prix }}</td>
                                <td>{{ ($produit->prix - $produit->prix_achat) * $etat['vendu'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7">TOTAL BENEFICE</td>
                            <td>{{ $ttbenefice }}</td>
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
        <p style="color:#000000 ">{{$agence->pied_page}}</p>
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
