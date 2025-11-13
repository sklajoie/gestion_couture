<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntreprise extends Model
{
    protected $fillable = [
        'designation',
        'code_barre',
        'reference',
        'stock',
        'prix',
        'stock_alerte',
        'couleur_id',
        'taille_id',
        'image',
        'user_id',
    ];

    public function couleur()
    {
        return $this->belongsTo(Couleur::class);
    }
    public function taille()
    {
        return $this->belongsTo(Taille::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
{
    static::creating(function ($model) {
        $now = \Carbon\Carbon::now();
        $prefix = $now->format('ym');

        do {
            $code = random_int(1000, 9999);
            $fullCode = "{$prefix}{$code}";
            $exists = self::where('code_barre', $fullCode)->exists();
        } while ($exists);

        $model->code_barre = $fullCode;
    });
}

}
