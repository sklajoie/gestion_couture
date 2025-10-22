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
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailApproStocks()
    {
        return $this->hasMany(DetailApproStock::class);
    }
}
