<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model {

    use HasFactory;

    protected $fillable = ['balance', 'user_id'];

    protected $casts = [
        'balance' => 'float',
        'user_id' => 'integer'
    ];

    public static function boot() {

        parent::boot();
        self::saving(
            function (Wallet $wallet) {

                $roundedBalance = round($wallet->balance, 2, PHP_ROUND_HALF_UP);
                $wallet->balance = $roundedBalance;
            }
        );
    }

    public function user() {

        return $this->belongsTo('App\Models\User');
    }
}
