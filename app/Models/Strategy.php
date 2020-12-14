<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model {

    use HasFactory;

    protected $fillable = [
        'type',
        'tenure',
        'yield',
        'relief',
    ];

    protected $casts = [

        'tenure' => 'integer',
        'yield'  => 'float',
        'relief' => 'float',
    ];

    public static function boot() {

        parent::boot();
        self::saving(
            function (Strategy $strategy) {

                $roundedYield = round($strategy->yield, 2, PHP_ROUND_HALF_UP);
                $roundedRelief = round($strategy->relief, 2, PHP_ROUND_HALF_UP);
                $strategy->yield = $roundedYield;
                $strategy->relief = $roundedRelief;
            }
        );
    }

    public function investments() {

        return $this->hasMany('App\Models\Investment', 'strategy_id');
    }
}
