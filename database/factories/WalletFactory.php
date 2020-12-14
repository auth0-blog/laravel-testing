<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory {

    protected $model = Wallet::class;

    public function definition()
    : array {

        return [
            'user_id' => User::factory()->create()->id,
            'balance' => $this->faker->randomNumber(6)
        ];
    }
}
