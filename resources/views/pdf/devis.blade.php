@if (isset($pdf))
    @php
        $reference = $devis->reference;
        $client = $devis->client->nom ?? 'N/A';
        $date = \Carbon\Carbon::parse($devis->date_devis)->format('d-m-Y');
    @endphp
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 10;
            $pageText = "Page " . $PAGE_NUM . " / " . $PAGE_COUNT;
            $footerText = "Réf: {{ $reference }} | Client: {{ $client }} | Émis le: {{ $date }}";
            $pdf->text(50, 820, $footerText, $font, $size);
            $pdf->text(500, 820, $pageText, $font, $size);
        }
    </script>
@endif


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{config('app.name')}}</title>

  <!-- Google Font: Source Sans Pro -->
  {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="//assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">
  <style> --}}
     
    body {
        font-family: sans-serif;
        font-size: 10px;
        margin: 10px;
    }
    table {
        width: 100%;
        /* margin-bottom: 20px; */
        font-size: 14px;
        border-collapse: collapse;
    }
    td {
        vertical-align: top;
        padding: 5px;
        border:1px solid:black;
    }
    th {
    vertical-align: top;
        padding: 5px;
        border:1px solid:black;
}
</style>
</head>
<body style="margin:10px">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
 

<!-- Title row -->
<table class="table1">
    <tr>
        <td colspan="2">
          
      {{-- <img src="{{ asset('storage/Logo/' . $devis->agence->logo) }}" width="80" alt="Logo"> <br> --}}

                          {{ $devis->agence->nom }} <br>
                 ADRESSE: {{ $devis->agence->adresse }} 
                 
       Date: {{ date('d-m-Y') }}
        </td>
        <td>
                TELEPHONE: {{ $devis->agence->telephone }}<br>
                  CONTACT: {{ $devis->agence->telephone }}<br>
                  EMAIL: {{ $devis->agence->telephone }}
           
        </td>
    </tr>
</table>

<!-- Info row -->
<table style="margin-bottom: 10px; ">
    <tr>
        <td width="33%">
            <strong>CLIENT</strong><br>
            <address>
             {{ $devis->client->nom ?? 'N/A' }}<br>
            <b>TELEPHONE:</b> {{ $devis->client->telephone ?? 'N/A' }}<br>
            <b>ADRESSE:</b> {{ $devis->client->adresse ?? 'N/A' }}<br>
            </address>
        </td>
        <td width="33%">
            <strong>DEVIS #{{ $devis->reference }}</strong><br>
            <address>
               <b>ÉMIS LE:</b>  {{ $devis->date_devis }}
            </address>
        </td>
        <td width="33%">
            <b>UTILISATEUR:</b> {{ $devis->user->name ?? 'N/A' }}<br>
            <b>TELEPHONE:</b> {{ $devis->user->telephone ?? 'N/A' }}<br>
        </td>
    </tr>
</table>


    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive" >
        <table class="table table-striped" style="border-collapse: collapse;" >
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
            <td>{{$devi->stockEntreprise->designation}}-
                {{$devi->stockEntreprise->couleur->nom}}-
                {{$devi->stockEntreprise->taille->nom}}-
                {{$devi->stockEntreprise->code_barre}}</td>
            <td>{{$devi->quantite}}</td>
            <td>{{$devi->prix_unitaire}}</td>
            <td>{{$devi->montant}}</td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
       
      </div>
      <!-- /.col -->
      <div class="col-6" >
        <div class="table-responsive">
          <table class="table" style="width:47%;" align="right">
            <tr>
              <th >TOTAL BRUT:</th>
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
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->


</body>


</html>
