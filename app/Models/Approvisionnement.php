<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
    protected $fillable = [
        'reference',
        'bon_commande_id',
        'date_operation',
        'user_id',
    ];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailAprovisionnement::class);
    }

    protected static function booted()
{
    static::deleting(function ($approvisionnement) {
        // dd($approvisionnement->details);
        foreach ($approvisionnement->details as $detail) {
            $ligne = DetailBonCommande::where('bon_commande_id', $approvisionnement->bon_commande_id)
                ->where('produit_id', $detail->produit_id)
                ->first();
            $produit = Produit::find($detail->produit_id);


            if ($ligne) {
                $quantiteExistante = $ligne->quantite_approvisionnee ?? 0;
                $quantiteRetiree = $detail->quantite;

                $nouvelleQuantite = max(0, $quantiteExistante - $quantiteRetiree);
                $estComplet = $nouvelleQuantite >= $ligne->quantite;

                $ligne->update([
                    'quantite_approvisionnee' => $nouvelleQuantite,
                    'is_approvisionne' => $estComplet,
                ]);
                if ($produit) {
                    $produit->decrement('stock', $quantiteRetiree);
                }
            }
        }

        $toutesApprovisionnees = DetailBonCommande::where('bon_commande_id', $approvisionnement->bon_commande_id)
            ->get()
            ->every(fn ($ligne) => $ligne->is_approvisionne);

        $bonCommande = BonCommande::find($approvisionnement->bon_commande_id);
        if ($bonCommande) {
            $bonCommande->update([
                'statut' => $toutesApprovisionnees ? 'Approvisionné' : 'Partiellement Approvisionné',
            ]);
        }

        // Supprimer les détails liés
        $approvisionnement->details()->delete();
    });
}

}
