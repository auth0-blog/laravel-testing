<?php

namespace Tests\Controllers;

use App\Models\Investment;
use App\Models\Strategy;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class InvestmentControllerTests extends TestCase {

    public function testIndexReturnsDataInValidFormat() {

        $this->json('get', 'api/investment')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'user_id',
                            'strategy_id',
                            'successful',
                            'amount',
                            'returns',
                            'created_at',
                        ]
                    ]
                ]
            );
    }

    public function testInvestmentIsCreatedSuccessfully() {

        $user = User::create(User::factory()->make()->getAttributes());
        $strategy = Strategy::create(Strategy::factory()->make()->getAttributes());
        $payload = [
            'user_id'     => $user->id,
            'strategy_id' => $strategy->id,
            'amount'      => $this->faker->randomNumber(4)
        ];
        $this->json('post', 'api/investment', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'user_id',
                        'strategy_id',
                        'successful',
                        'amount',
                        'returns',
                        'created_at',
                    ]
                ]
            );
        $this->assertDatabaseHas('investments', $payload);
    }

    public function testStoreWithMissingData() {

        $payload = [
            'amount' => $this->faker->randomNumber(4)
        ];
        $this->json('post', 'api/investment', $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['error']);
    }

    public function testStoreWithMissingUserAndStrategy() {

        $payload = [
            'user_id'     => 0,
            'strategy_id' => 0,
            'amount'      => $this->faker->randomNumber(4)
        ];
        $this->json('post', 'api/investment', $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

    public function testInvestmentIsShownCorrectly() {

        $user = User::create(User::factory()->make()->getAttributes());
        $strategy = Strategy::create(Strategy::factory()->make()->getAttributes());
        $isSuccessful = $this->faker->boolean;
        $investmentAmount = $this->faker->randomNumber(6);
        $investmentReturns = $isSuccessful ?
            $investmentAmount * $strategy->yield :
            $investmentAmount * $strategy->relief;
        $investment = Investment::create(
            [
                'user_id'     => $user->id,
                'strategy_id' => $strategy->id,
                'successful'  => $isSuccessful,
                'amount'      => $investmentAmount,
                'returns'     => $investmentReturns
            ]
        );

        $this->json('get', "api/investment/$investment->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => [
                        'id'          => $investment->id,
                        'user_id'     => $investment->user->id,
                        'strategy_id' => $investment->strategy->id,
                        'successful'  => $isSuccessful,
                        'amount'      => round($investment->amount, 2, PHP_ROUND_HALF_UP),
                        'returns'     => round($investment->returns, 2, PHP_ROUND_HALF_UP),
                        'created_at'  => (string)$investment->created_at,
                    ]
                ]
            );
    }

    public function testShowMissingInvestment() {

        $this->json('get', "api/investment/0")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

    public function testDestroyInvestment() {

        $user = User::create(User::factory()->make()->getAttributes());
        $strategy = Strategy::create(Strategy::factory()->make()->getAttributes());
        $isSuccessful = $this->faker->boolean;
        $investmentAmount = $this->faker->randomNumber(6);
        $investmentReturns = $isSuccessful ?
            $investmentAmount * $strategy->yield :
            $investmentAmount * $strategy->relief;
        $investment = Investment::create(
            [
                'user_id'     => $user->id,
                'strategy_id' => $strategy->id,
                'successful'  => $isSuccessful,
                'amount'      => $investmentAmount,
                'returns'     => $investmentReturns
            ]
        );
        $this->json('delete', "api/investment/$investment->id")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateInvestment() {

        $user = User::create(User::factory()->make()->getAttributes());
        $strategy = Strategy::create(Strategy::factory()->make()->getAttributes());

        $isSuccessful = $this->faker->boolean;
        $investmentAmount = $this->faker->randomNumber(6);
        $investmentReturns = $isSuccessful ?
            $investmentAmount * $strategy->yield :
            $investmentAmount * $strategy->relief;
        $investment = Investment::create(
            [
                'user_id'     => $user->id,
                'strategy_id' => $strategy->id,
                'successful'  => $isSuccessful,
                'amount'      => $investmentAmount,
                'returns'     => $investmentReturns
            ]
        );
        $payload = [
            'id'         => $investment->id,
            'successful' => !$isSuccessful
        ];

        $this->json('put', "api/investment/$investment->id", $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}