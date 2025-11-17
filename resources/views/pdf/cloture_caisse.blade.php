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
                    @if ($cloture->agence->logo)
                        @php
                            $logoPath = public_path("storage/".$cloture->agence->logo);
                            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoSrc = "data:image/{$logoType};base64,{$logoData}";
                        @endphp
                        <img src="{{ $logoSrc }}" width="80" alt="Logo de l'entreprise">
                    @else
                        <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('logo/logo.jpg'))) }}" width="80" alt="Logo de l'entreprise">
                    @endif
                    <br><br>
                      <strong>{{ $cloture->agence->nom?? 'N/A' }}</strong><br>
                    {{ $cloture->agence->adresse ?? 'N/A'}}<br>
                </td>
                <td width="33%" style="text-align: center;">
                  
                </td>
                <td width="33%" style="text-align: right;">
                    {{ $cloture->agence->telephone?? 'N/A' }}<br>
                    {{ $cloture->agence->contact?? 'N/A' }}<br>
                    {{ $cloture->agence->email?? 'N/A' }} <br><br>
                    {{$cloture->agence->ville}} Le: {{ date('d-m-Y') }}
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
                        <b>UTILISATEUR:</b> {{ $cloture->user->name ?? 'N/A' }}<br>
                        <b>TELEPHONE:</b> {{ $cloture->user->telephone ?? 'N/A' }}<br>
                    </td>
                    <td width="40%">
                        <strong>CLOTURE #{{ $cloture->reference }}</strong><br>
                        <address>
                        <b>DU: </b>{{ date('d F Y', strtotime($cloture->date))}}
                        </address>
                    </td>
                   
                </tr>
            </table>

            <!-- Table produits -->
            <div class="table-responsive">
                <p style="text-align: center">LISTE DES DEVIS </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>REFERENCE</th>
                            <th>TOTAL BRUT</th>
                            <th>REMISE</th>
                            <th>MONTANT HORS TAXE</th>
                            <th>TVA</th>
                            <th>MONTANT TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $ttdevis = 0; @endphp
                        @foreach ($devis as $devi )
                        @php $ttdevis += $devi->montant_ttc; @endphp
                            <tr>
                                <td>{{$devi->reference}}</td>
                                <td>{{$devi->montant_brut}}</td>
                                <td>{{$devi->remise}}</td>
                                <td>{{$devi->montant_hors_taxe}}</td>
                                <td>{{$devi->tva}}</td>
                                <td>{{$devi->montant_ttc}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">TOTAL</td>
                            <td>{{ $ttdevis}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <p style="text-align: center">LISTE DES VENTES NON REGLEES</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>REFERENCE</th>
                            <th>TOTAL BRUT</th>
                            <th>REMISE</th>
                            <th>MONTANT HORS TAXE</th>
                            <th>TVA</th>
                            <th>MONTANT TOTAL</th>
                            <th>ACOMPTE</th>
                            <th>SOLDE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $ttventecrs = 0; $ttavance = 0; $ttsolde = 0; @endphp
                        @foreach ($vente_en_cours as $ventecrs )
                        @php 
                        $ttventecrs += $ventecrs->montant_ttc;
                         $ttavance += $ventecrs->avance;
                         $ttsolde += $ventecrs->solde;
                         @endphp
                            <tr>
                                <td>{{$ventecrs->reference}}</td>
                                <td>{{$ventecrs->montant_brut}}</td>
                                <td>{{$ventecrs->remise}}</td>
                                <td>{{$ventecrs->montant_hors_taxe}}</td>
                                <td>{{$ventecrs->tva}}</td>
                                <td>{{$ventecrs->montant_ttc}}</td>
                                <td>{{$ventecrs->avance}}</td>
                                <td>{{$ventecrs->solde}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">TOTAL</td>
                            <td>{{ $ttventecrs}}</td>
                            <td>{{ $ttavance}}</td>
                            <td>{{ $ttsolde}}</td>
                        </tr>
                        {{-- <tr>
                            <td colspan="7">TOTAL AVANCE</td>
                        </tr>
                        <tr>
                            <td colspan="7">TOTAL SOLDE</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <p style="text-align: center">LISTE DES VENTES REGLEES</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>REFERENCE</th>
                            <th>TOTAL BRUT</th>
                            <th>REMISE</th>
                            <th>MONTANT HORS TAXE</th>
                            <th>TVA</th>
                            <th>MONTANT TOTAL</th>
                            <th>ACOMPTE</th>
                            <th>SOLDE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $ttvente = 0;  $ttavancev = 0; $ttsoldev = 0; @endphp
                        @foreach ($ventes as $vente )
                        @php $ttvente += $vente->montant_ttc;
                        $ttavancev += $vente->avance;
                         $ttsoldev += $vente->solde;
                        @endphp
                            <tr>
                                <td>{{$vente->reference}}</td>
                                <td>{{$vente->montant_brut}}</td>
                                <td>{{$vente->remise}}</td>
                                <td>{{$vente->montant_hors_taxe}}</td>
                                <td>{{$vente->tva}}</td>
                                <td>{{$vente->montant_ttc}}</td>
                                <td>{{$vente->avance}}</td>
                                <td>{{$vente->solde}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">TOTAL</td>
                            <td>{{ $ttvente}}</td>
                             <td>{{ $ttavancev}}</td>
                            <td>{{ $ttsoldev}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <p style="text-align: center">LISTE DES RECROUVEMENTS</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>REFERENCE</th>
                            <th>FACTURE</th>
                            <th>MONTANT</th>
                            <th>DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $ttrecouvr = 0; @endphp
                        @foreach ($recrouvements as $recrouvt )
                        @php 
                         $ttrecouvr += $recrouvt->montant;
                        @endphp
                            <tr>
                                <td>{{$recrouvt->reference}}</td>
                                <td>{{$recrouvt->vente->reference}}</td>
                                <td>{{$recrouvt->montant}}</td>
                                <td>{{date('d-m-Y H:i', strtotime( $recrouvt->created_at))}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">TOTAL</td>
                            <td>{{ $ttrecouvr}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <p style="text-align: center">VERSEMENTS ET RECROUVEMENTS</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>MODE</th>
                            <th>MONTANT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalcassie = 0; @endphp
                        @foreach ($encaisse_jour  as $encaisse )
                        @php 
                         $totalcassie += $encaisse->total;
                        @endphp
                            <tr>
                                <td>{{$encaisse->mode_paiement}}</td>
                                <td>{{$encaisse->total}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="1">TOTAL</td>
                            <td>{{ $totalcassie}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <p style="text-align: center">LISTE DES MOUVEMENTS CAISSE </p>
             <table style="width: 100%; font-size: 11px; table-layout: fixed; word-wrap: break-word;">
                <thead>
                    <tr>
                        <th style="width: 80px;">REFERENCE</th>
                        <th style="width: 60px;">TYPE</th>
                        <th style="width: 100px;">NATURE</th>
                        <th style="width: 60px;">MONTANT</th>
                        <th style="width: 40px;">MODE</th>
                        {{-- <th style="width: 80px;">STRUCTURE</th>
                        <th style="width: 80px;">CAISSE</th> --}}
                        <th style="width: 120px;">BENEFICIAIRE</th>
                        <th style="width: 80px;">DATE</th>
                        {{-- <th style="width: 100px;">DETAIL</th> --}}
                        <th style="width: 100px;">UTILISATEUR</th>
                    </tr>
                </thead>
                <tbody>
                    @php  $ttrest=0; $ttmvmtentre =0;  $ttmvmtsorti =0; $structure = null;$nomstructure = "-"; @endphp
                        @foreach ($mouvementcaisse as $mouvementgroup )
                           @php
                           if($mouvementgroup->type_mouvement =="ENTREE EN CAISSE")
                           {
                               $ttmvmtentre += $mouvementgroup->montant;
                           }elseif($mouvementgroup->type_mouvement =="SORTIE DE CAISSE")
                           {
                               $ttmvmtsorti += $mouvementgroup->montant;
                           }
                           $ttrest =$ttmvmtentre - $ttmvmtsorti;
                                   
                                    if ($mouvementgroup->structure_type == "AGENCE") {
                                        $structure = App\Models\Agence::find($mouvementgroup->structure_id);
                                    } elseif ($mouvementgroup->structure_type == "ATELIER") {
                                        $structure = App\Models\Atelier::find($mouvementgroup->structure_id);
                                    } else {
                                        $nomstructure ="-";
                                            }

                         $nomstructure = $structure->nom . ' - ' . $structure->ville;
                       @endphp
                            <tr>
                                <td>
                                    {{$mouvementgroup->reference}}
                                </td>
                                <td>{{$mouvementgroup->type_mouvement}}</td>
                                <td>{{$mouvementgroup->mouvementNature->nom}}</td>
                                <td>{{$mouvementgroup->montant}}</td>
                                <td>{{$mouvementgroup->mode_reglement}}</td>
                                {{-- <td> {{$nomstructure}}</td>
                                <td>{{$mouvementgroup->caisse->nom}}</td> --}}
                                <td>{{$mouvementgroup->employe->nom}} {{$mouvementgroup->employe->prenom}}</td>
                                <td>{{date('d F Y', strtotime($mouvementgroup->date))}}</td>
                                {{-- <td>{{$mouvementgroup->detail}}</td> --}}
                                <td>{{$mouvementgroup->user->name}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7">TOTAL ENTREE</td>
                            <td >{{$ttmvmtentre}}</td>
                        </tr>
                        <tr>
                            <td colspan="7">TOTAL SORTIE</td>
                            <td >{{$ttmvmtsorti}}</td>
                        </tr>
                        <tr>
                            <td colspan="7">ETAT RESTE</td>
                            <td >{{ $ttrest}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive" style="font-size:16px;margin-right:50px;font-weight:bold;">
                <p style="text-align: center">MONTANT EN CAISSE</p>
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th>CAISSE</th>
                            <th>MONTANT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalcassie = 0; @endphp
                        @foreach ($montant_encaisse  as $montantencaisse )
                        @php 
                         $totalcassie += $montantencaisse->total;
                        @endphp
                            <tr>
                                <td>{{$montantencaisse->caisse->nom?? 'N/A'}}</td>
                                <td>{{$montantencaisse->total}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="1">TOTAL</td>
                            <td>{{ $totalcassie}}</td>
                        </tr>
                        <tr>
                            <td colspan="1">TOTAL - MOUVEMENT</td>
                            <td>{{ $totalcassie +  $ttrest}}</td>
                        </tr>
                    </tbody>
                </table>

                <br>
                <br>
                 <div align="right" style="font-size:16px;margin-right:50px;font-weight:bold;">
                        <p><u>Signature & Cachet</u></p>
                    </div>
            </div>
        </section>
    </div>

    <footer>
        <p style="color:#000000 ">{{$cloture->agence->pied_page}}</p>
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
