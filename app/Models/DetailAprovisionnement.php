<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetailAprovisionnement extends Model
{
    protected $fillable = [
        'approvisionnement_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'total',
        'entreprise_id',
    ];

    public function approvisionnement()
    {
        return $this->belongsTo(Approvisionnement::class);
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }


        protected static function booted()
{

static::creating(function ($model) {
    $approvisionnement = $model->approvisionnement()->first();
    if (! $approvisionnement) {
        return;
    }

    $bonCommande = BonCommande::find($approvisionnement->bon_commande_id);
    if (! $bonCommande) {
        return;
    }
    //dd($bonCommande);
    $detail = DetailBonCommande::where('bon_commande_id', $bonCommande->id)
        ->where('produit_id', $model->produit_id)
        ->first();

    if (! $detail) {
        return;
    }

    $quantiteCommandee = $detail->quantite;
    $quantiteExistante = $detail->quantite_approvisionnee ?? 0;
    $quantiteAjoutee   = $model->quantite;

    if ($quantiteExistante + $quantiteAjoutee > $quantiteCommandee) {
        $quantiteAjoutee = max(0, $quantiteCommandee - $quantiteExistante);
    }

    $nouvelleQuantite = min($quantiteCommandee, $quantiteExistante + $quantiteAjoutee);
    $estComplet       = $nouvelleQuantite == $quantiteCommandee;

    $detail->update([
        'quantite_approvisionnee' => $nouvelleQuantite,
        'is_approvisionne'        => $estComplet,
    ]);

    $produit = Produit::find($detail->produit_id);
    if ($produit) {
        if ($produit->emplacement === 'tout' || $produit->emplacement === 'atelier') {
            $produit->increment('stock', $quantiteAjoutee);
        } elseif ($produit->emplacement === 'tout' || $produit->emplacement === 'boutique') {
            $checkStock = StockEntreprise::where('reference', $produit->code_barre)->first();
            if ($checkStock) {
                $checkStock->increment('stock', $quantiteAjoutee);
            } else {
                StockEntreprise::create([
                    'designation'   => $produit->nom,
                    'reference'     => $produit->code_barre,
                    'stock'         => $quantiteAjoutee,
                    'prix'          => $produit->prix_vente,
                    'prix_achat'    => $model->prix_unitaire,
                    'stock_alerte'  => 1,
                    'couleur_id'    => $produit->couleur_id,
                    'taille_id'     => $produit->taille_id,
                    'categorie_produit_id'     => $produit->categorie_produit_id,
                    'marque_id'     => $produit->marque_id,
                    'image'         => $produit->image,
                    'user_id'       => Auth::id(),
                    'type'           => "produit",
                ]);
            }
        }
    }

    $toutesApprovisionnees = DetailBonCommande::where('bon_commande_id', $bonCommande->id)
        ->get()
        ->every(fn ($ligne) => $ligne->is_approvisionne);

    $bonCommande->update([
        'statut' => $toutesApprovisionnees ? 'Approvisionné' : 'Partiellement Approvisionné',
    ]);
});


static::updating(function ($model) {
    $bonCommande = BonCommande::find($model->approvisionnement->bon_commande_id);
    if (! $bonCommande) {
        return;
    }

    $ancienproduit_id = $model->getOriginal('produit_id');
    //dd( $model->produit_id);
    $ancienneQuantite  = $model->getOriginal('quantite');
    $nouvelleQuantite  = $model->quantite;
    $deltaQuantite     = $nouvelleQuantite - $ancienneQuantite;

    $detail = DetailBonCommande::where('bon_commande_id', $bonCommande->id)
        ->where('produit_id',  $ancienproduit_id)
        ->first();

    if (! $detail) {
        return;
    }

    $qteCommandee      = $detail->quantite ?? 0;
    $quantiteExistante = $detail->quantite_approvisionnee ?? 0;

    // Nouvelle quantité approvisionnée calculée
    $nouvelleQteAppro = $quantiteExistante + $deltaQuantite;

    //Limiter à la quantité commandée
    if ($nouvelleQteAppro > $qteCommandee) {
        $nouvelleQteAppro = $qteCommandee;
        // Ajuster le delta pour ne pas dépasser
        $deltaQuantite = $qteCommandee - $quantiteExistante;
    }

    // Mise à jour de la ligne de bon de commande
    $detail->update([
        'quantite_approvisionnee' => $nouvelleQteAppro,
        'is_approvisionne'        => $nouvelleQteAppro == $qteCommandee,
    ]);

    // Mise à jour du stock
    if ($deltaQuantite !== 0) {
        $produit = Produit::find($ancienproduit_id);
        if ($produit) {
            if ( $produit->emplacement === 'atelier') {
                $produit->increment('stock', $deltaQuantite);
            } elseif ( $produit->emplacement === 'boutique') {
                $checkStock = StockEntreprise::where('reference', $produit->code_barre)->first();
                if ($checkStock) {
                    $checkStock->increment('stock', $deltaQuantite);
                }
            } elseif ( $produit->emplacement === 'tout') {
                $produit->increment('stock', $deltaQuantite);
                $checkStock = StockEntreprise::where('reference', $produit->code_barre)->first();
                if ($checkStock) {
                    $checkStock->increment('stock', $deltaQuantite);
                }
            }
        }
    }

    // Mise à jour du statut du bon de commande
    $details = DetailBonCommande::where('bon_commande_id', $bonCommande->id)->get();
    if ($details->every(fn ($ligne) => $ligne->is_approvisionne)) {
        $bonCommande->update(['statut' => 'Approvisionné']);
    } elseif ($details->contains(fn ($ligne) => $ligne->quantite_approvisionnee > 0)) {
        $bonCommande->update(['statut' => 'Partiellement Approvisionné']);
    } else {
        $bonCommande->update(['statut' => 'en attente']);
    }
});






}
}
