<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model {

    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email'
    ];

    public function wallet() {

        return $this->hasOne('App\Models\Wallet');
    }

    public function investments() {

        return $this->hasMany('App\Models\Investment', 'user_id');
    }
}
