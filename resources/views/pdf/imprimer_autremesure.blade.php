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
                <p style="text-align: center">AUTRE MESURE </p>
                @foreach ($autremesures as $autremesure)
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                    <tr>
                        <td style="width: 45%; vertical-align: top; border: none;">

                            {{-- Tableau gauche --}}
                            <table style="width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th style="width:25% !important;">Référence</th>
                                        <th>Date</th>
                                        <th style="width:40% !important;">Model</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $autremesure->Reference }}</td>
                                        <td>{{ date('d-m-Y à H:i', strtotime($autremesure->created_at)) }}</td>
                                        @if ($autremesure->Model_mesure)
                                            @php
                                            //dd($autremesure->Model_mesure);
                                                $logoPathm = public_path("storage/".$autremesure->Model_mesure[0]?? null);
                                                $logoTypem = pathinfo($logoPathm, PATHINFO_EXTENSION);
                                                $logoDatam = base64_encode(file_get_contents($logoPathm));
                                                $logoSrcm = "data:image/{$logoTypem};base64,{$logoDatam}";
                                            @endphp
                                            <td>  <img src="{{ $logoSrcm }}" width="90px" alt="Autre Mesure"> </td>
                                            @else
                                            <td></td>
                                            @endif
                            </tr>
                                </tbody>
                            </table>

                        </td>

                        <td style="width: 55%; vertical-align: top; border: none;">

                            {{-- Tableau droite --}}
                            <table style="width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Mesure</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($autremesure->autreMesureDetail as $detail)
                                        <tr>
                                            <td>{{ $detail->nom ?? '-' }}</td>
                                            <td>{{ $detail->mesure ?? '-' }}</td>
                                            <td>{{ $detail->detail ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </td>
                    </tr>
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
