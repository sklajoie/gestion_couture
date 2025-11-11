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
            margin: 100px 40px 100px 40px;
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
        table {
        font-size: 10px;
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

            <!-- Table produits -->
            <div class="table-responsive">
                <p style="text-align: center">MESURE ENSEMBLE </p>
                @foreach ($ensembles as $ensemble)
    {{-- Tableau CHEMISE --}}
    <table style="width: 100%; font-size: 12px; table-layout: fixed; word-wrap: break-word; border-collapse: collapse; margin-bottom: 10px;">
        <caption>CHEMISE</caption>
        <thead>
            {{-- <tr><th colspan="13" style="text-align: left;">CHEMISE</th></tr> --}}
            <tr>
                <th style="width:8% !important;">Référence</th>
                <th >Cou</th>
                <th>Poitrine</th>
                <th>Taille</th>
                <th>Bassin</th>
                <th>Épaule</th>
                <th>Manche</th>
                <th>Bas</th>
                <th>Poignet</th>
                <th>Longueur</th>
                <th style="width:11% !important;">Détail</th>
                <th style="width:8% !important;">Date</th>
                <th  style="width:12% !important;">Model</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td >{{ $ensemble->Reference }}</td>
                <td>{{ $ensemble->Tour_cou }}</td>
                <td>{{ $ensemble->Tour_poitrine }}</td>
                <td>{{ $ensemble->Tour_taille }}</td>
                <td>{{ $ensemble->Tour_bassin }}</td>
                <td>{{ $ensemble->Largeur_epaule }}</td>
                <td>{{ $ensemble->Longueur_manche }}</td>
                <td>{{ $ensemble->Tour_bas }}</td>
                <td>{{ $ensemble->Tour_poignet }}</td>
                <td>{{ $ensemble->Longueur_ensemble }}</td>
                <td>{{ $ensemble->Description }}</td>
                <td>{{ date('d-m-Y à H:i', strtotime($ensemble->created_at)) }}</td>
                <td>
                    @php
                        $logoSrcm = null;
                        if (is_array($ensemble->Model_mesure) && isset($ensemble->Model_mesure[0])) {
                            $pathm = public_path("storage/" . $ensemble->Model_mesure[0]);
                            if (file_exists($pathm)) {
                                $typem = pathinfo($pathm, PATHINFO_EXTENSION);
                                $datam = base64_encode(file_get_contents($pathm));
                                $logoSrcm = "data:image/{$typem};base64,{$datam}";
                            }
                        }
                    @endphp
                    @if ($logoSrcm)
                        <img src="{{ $logoSrcm }}" width="90px" alt="Chemise">
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Tableau PANTALON --}}
    <table style="width: 100%; font-size: 12px; table-layout: fixed; word-wrap: break-word; border-collapse: collapse; margin-bottom: 30px;">
             <caption>PANTALON</caption>
        <thead>
            {{-- <tr><th colspan="12" style="text-align: left;">PANTALON</th></tr> --}}
            <tr>
                <th>Taille</th>
                <th>Bassin</th>
                <th>Cuisse</th>
                <th>Genou</th>
                <th>Bas</th>
                <th>H. Genou</th>
                <th>H. Cheville</th>
                <th>Entre Jambe</th>
                <th>Longueur</th>
                <th style="width:11% !important;">Détail</th>
                <th style="width:8% !important;">Date</th>
                <th style="width:12% !important;"> Model</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $ensemble->Tour_taille }}</td>
                <td>{{ $ensemble->Tour_bassin }}</td>
                <td>{{ $ensemble->Tour_cuisse }}</td>
                <td>{{ $ensemble->Tour_genou }}</td>
                <td>{{ $ensemble->Tour_bas }}</td>
                <td>{{ $ensemble->Hauteur_genou }}</td>
                <td>{{ $ensemble->Hauteur_cheville }}</td>
                <td>{{ $ensemble->Entre_jambe }}</td>
                <td>{{ $ensemble->Longueur_ensemble }}</td>
                <td>{{ $ensemble->Description }}</td>
                <td>{{ date('d-m-Y à H:i', strtotime($ensemble->created_at)) }}</td>
                <td>
                    @php
                        $logoSrcp = null;
                        if (is_array($ensemble->Model_mesure) && isset($ensemble->Model_mesure[1])) {
                            $pathp = public_path("storage/" . $ensemble->Model_mesure[1]);
                            if (file_exists($pathp)) {
                                $typep = pathinfo($pathp, PATHINFO_EXTENSION);
                                $datap = base64_encode(file_get_contents($pathp));
                                $logoSrcp = "data:image/{$typep};base64,{$datap}";
                            }
                        }
                    @endphp
                    @if ($logoSrcp)
                        <img src="{{ $logoSrcp }}" width="90px" alt="Pantalon">
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endforeach


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
