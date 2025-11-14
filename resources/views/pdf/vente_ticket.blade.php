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
        @if ($vente->agence->logo)
            @php
                $logoPath = public_path("storage/".$vente->agence->logo);
                $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoSrc = "data:image/{$logoType};base64,{$logoData}";
            @endphp
            <img src="{{ $logoSrc }}" width="60"><br>
        @endif
        <div class="bold">{{ $vente->agence->nom }}</div>
        {{ $vente->agence->adresse }}<br>
        {{ $vente->agence->telephone }}<br>
        {{ $vente->agence->email }}
    </div>

    <hr>

    <div>
        <strong>Vente #{{ $vente->reference }}</strong><br>
        Date : {{ date('d-m-Y H:i', strtotime($vente->date_vente)) }}<br>
        Client : {{ $vente->client->nom ?? 'N/A' }}
        Client : {{ $vente->client->telephone ?? 'N/A' }}
        Client : {{ $vente->client->adresse ?? 'N/A' }}
    </div>

    <hr>

    <table>
        @foreach ($vente->detailVentes as $item)
            <tr>
                <td colspan="2">{{ $item->stockEntreprise->designation?? 'N/A' }}
                     {{ $item->stockEntreprise->code_barre?? 'N/A' }}
                     {{ $item->stockEntreprise->taille->nom?? 'N/A'}}
                     {{ $item->stockEntreprise->couleur->nom?? 'N/A'}}
                    </td>
            </tr>
            <tr>
                <td>{{ $item->quantite }}</td>
                <td style="text-align:right;">{{ number_format($item->montant, 0, ',', ' ') }} FCFA</td>
            </tr>
        @endforeach
    </table>

    <hr>

    <table>
        <tr>
            <td>Total TTC</td>
            <td style="text-align:right;">{{ number_format($vente->montant_ttc, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td>Avance</td>
            <td style="text-align:right;">{{ number_format($vente->avance, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td>Reste</td>
            <td style="text-align:right;">{{ number_format($vente->solde, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <hr>

    <div class="center">
        <i>Merci pour votre achat !</i><br>
        {{ date('d-m-Y H:i') }}
    </div>
</body>
</html>
