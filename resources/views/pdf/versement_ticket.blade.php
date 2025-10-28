<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket de caisse</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 5mm;
        }

        body {
            width: 80mm;
            font-family: monospace;
            font-size: 12px;
            margin: 0;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="center">
        @if ($entreprise->logo)
            @php
                $logoPath = public_path("storage/".$entreprise->logo);
                $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoSrc = "data:image/{$logoType};base64,{$logoData}";
            @endphp
            <img src="{{ $logoSrc }}" width="60"><br>
        @endif
        <div class="bold">{{ $entreprise->nom }}</div>
        {{ $entreprise->adresse }}<br>
        {{ $entreprise->telephone }}<br>
        {{ $entreprise->email }}
    </div>

    <hr>

    {{-- <div>
        <strong>Vente #{{ $vente->reference }}</strong><br>
        Date : {{ date('d-m-Y H:i', strtotime($vente->date_vente)) }}<br>
        Client : {{ $vente->client->nom ?? 'N/A' }}
        Client : {{ $vente->client->telephone ?? 'N/A' }}
        Client : {{ $vente->client->adresse ?? 'N/A' }}
    </div> --}}

    {{-- <hr> --}}

   <table>
    <thead>
        <tr>
            <th>FACTURE</th>
            <th>VERSEMENT</th>
            <th>MONTANT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($versementsParFacture as $venteId => $versements)
            @php $vente = $ventes[$venteId] ?? null; @endphp
            @if ($vente)
                @foreach ($versements as $index => $versemt)
                    <tr>
                        @if ($index === 0)
                            <td rowspan="{{ count($versements) }}">{{ $vente->reference }}</td>
                        @endif
                        <td>{{ $versemt->reference }}</td>
                        <td style="text-align:right;">{{ number_format($versemt->montant, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>


    <hr>

    <div class="center">
        <i>Merci pour votre achat !</i><br>
        {{ date('d-m-Y H:i') }}
    </div>
</body>
</html>
