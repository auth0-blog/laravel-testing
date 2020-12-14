<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrategySeeder extends Seeder {

    public function run()
    : void {

        $faker = Factory::create();
        $strategyTypes = [
            'Long/Short Equity',
            'Equity Market Neutral',
            'Merger Arbitrage',
            'Global Macro',
            'Volatility Arbitrage'
        ];

        foreach ($strategyTypes as $strategyType) {

            DB::table('strategies')->insert(
                [
                    'type'   => $strategyType,
                    'tenure' => $faker->numberBetween(1, 10),
                    'yield'  => $faker->numberBetween(1, 10000) * 0.01,
                    'relief' => $faker->numberBetween(1, 100) * 0.001
                ]
            );
        }
    }
}
