<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovisionnementStock extends Model
{
    protected $fillable = [
        'reference',
        'date_operation',
        'total_appro',
        'user_id',
        'entreprise_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailApproStocks()
    {
        return $this->hasMany(DetailApproStock::class);
    }



          protected static function booted()
    {
        static::creating(function ($model) {
            $now = \Carbon\Carbon::now();
            $prefix = $now->format('ym');

            do {
                $suffix = str_pad(random_int(1, 99999), 4, '0', STR_PAD_LEFT);
                $numero = "AS{$prefix}{$suffix}";
            } while (self::where('reference', $numero)->exists());

            $model->reference = $numero;
        });
    }
}
