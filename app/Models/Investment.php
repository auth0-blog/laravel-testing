<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model {

    use HasFactory;

    protected $fillable = [
        'user_id',
        'strategy_id',
        'successful',
        'amount',
        'returns'
    ];

    protected $casts = [
        'user_id'     => 'integer',
        'strategy_id' => 'integer',
        'successful'  => 'boolean',
        'amount'      => 'float',
        'returns'     => 'float'
    ];

    public static function boot() {

        parent::boot();
        self::saving(
            function (Investment $investment) {

                $roundedAmount = round($investment->amount, 2, PHP_ROUND_HALF_UP);
                $roundedReturns = round($investment->returns, 2, PHP_ROUND_HALF_UP);
                $investment->amount = $roundedAmount;
                $investment->returns = $roundedReturns;
            }
        );
    }

    public function strategy() {

        return $this->belongsTo('App\Models\Strategy');
    }

    public function user() {

        return $this->belongsTo('App\Models\User');
    }

}
