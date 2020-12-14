<?php

namespace Tests\Controllers;

use App\Models\Strategy;
use Illuminate\Http\Response;
use Tests\TestCase;

class StrategyControllerTests extends TestCase {

    public function testIndexReturnsDataInValidFormat() {

        $this->json('get', 'api/strategy')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'type',
                            'tenure',
                            'yield',
                            'relief',
                            'investments' => [
                                '*' => [
                                    'id',
                                    'user_id',
                                    'strategy_id',
                                    'successful',
                                    'amount',
                                    'returns',
                                    'created_at',
                                ]
                            ],
                            'created_at',

                        ]
                    ]
                ]
            );
    }

    public function testStrategyIsCreatedSuccessfully() {

        $payload = Strategy::factory()->make()->getAttributes();
        $this->json('post', 'api/strategy', $payload)
            ->assertStatus(Response::HTTP_CREATED)->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'type',
                        'tenure',
                        'yield',
                        'relief',
                        'investments',
                        'created_at',
                    ]
                ]
            );
        $this->assertDatabaseHas('strategies', $payload);
    }

    public function testStrategyIsShownCorrectly() {

        $strategy = Strategy::create(Strategy::factory()->make()->getAttributes());
        $this->json('get', "api/strategy/$strategy->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => [
                        'id'          => $strategy->id,
                        'type'        => $strategy->type,
                        'tenure'      => $strategy->tenure,
                        'yield'       => round($strategy->yield, 2, PHP_ROUND_HALF_UP),
                        'relief'      => round($strategy->relief, 2, PHP_ROUND_HALF_UP),
                        'investments' => $strategy->investments,
                        'created_at'  => (string)$strategy->created_at,
                    ]
                ]
            );
    }

    public function testUpdateMissingStrategy() {

        $this->json('put', 'api/strategy/0', Strategy::factory()->make()->getAttributes())
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

    public function testDestroyMissingStrategy() {

        $this->json('delete', 'api/strategy/0')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['error']);
    }

    public function testStrategyIsUpdatedSuccessfully() {

        $strategy = Strategy::create(Strategy::factory()->make()->getAttributes());
        $payload = Strategy::factory()->make();

        $this->json('put', "api/strategy/$strategy->id", $payload->getAttributes())
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => [
                        'id'          => $strategy->id,
                        'type'        => $payload['type'],
                        'tenure'      => $payload['tenure'],
                        'yield'       => round($payload['yield'], 2, PHP_ROUND_HALF_UP),
                        'relief'      => round($payload['relief'], 2, PHP_ROUND_HALF_UP),
                        'investments' => $strategy->investments,
                        'created_at'  => (string)$strategy->created_at,
                    ]
                ]
            );
    }

    public function testStrategyIsDestroyedSuccessfully() {

        $strategyAttributes = Strategy::factory()->make()->getAttributes();
        $strategy = Strategy::create($strategyAttributes);
        $this->json('delete', "api/strategy/$strategy->id")
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
        $this->assertDatabaseMissing('strategies', $strategyAttributes);
    }
}