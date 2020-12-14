<?php

namespace Tests\Controllers;

use Illuminate\Http\Response;
use Tests\TestCase;

use App\Models\User;
use App\Models\Wallet;

use App\Models\Investment;
use App\Models\Strategy;

class UserControllerTests extends TestCase
{
    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/user')
         ->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure(
             [
                 'data' => [
                     '*' => [
                         'id',
                         'first_name',
                         'last_name',
                         'email',
                         'created_at',
                         'wallet' => [
                             'id',
                             'balance'
                         ]
                     ]
                 ]
             ]
         );
    }


    public function testUserIsCreatedSuccessfully()
    {
        $payload = [
        'first_name' => $this->faker->firstName,
        'last_name'  => $this->faker->lastName,
        'email'      => $this->faker->email
    ];
        $this->json('post', 'api/user', $payload)
         ->assertStatus(Response::HTTP_CREATED)
         ->assertJsonStructure(
             [
                 'data' => [
                     'id',
                     'first_name',
                     'last_name',
                     'email',
                     'created_at',
                     'wallet' => [
                         'id',
                         'balance'
                     ]
                 ]
             ]
         );
        $this->assertDatabaseHas('users', $payload);
    }


    public function testUserIsShownCorrectly()
    {
        $user = User::create(
            [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email
        ]
        );
        Wallet::create(
            [
            'balance' => 0,
            'user_id' => $user->id
        ]
        );

        $this->json('get', "api/user/$user->id")
         ->assertStatus(Response::HTTP_OK)
         ->assertExactJson(
             [
                 'data' => [
                     'id'         => $user->id,
                     'first_name' => $user->first_name,
                     'last_name'  => $user->last_name,
                     'email'      => $user->email,
                     'created_at' => (string)$user->created_at,
                     'wallet'     => [
                         'id'      => $user->wallet->id,
                         'balance' => $user->wallet->balance
                     ]
                 ]
             ]
         );
    }


    public function testUserIsDestroyed()
    {
        $userData =
        [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email
        ];
        $user = User::create(
            $userData
        );

        $this->json('delete', "api/user/$user->id")
         ->assertNoContent();
        $this->assertDatabaseMissing('users', $userData);
    }


    public function testUpdateUserReturnsCorrectData()
    {
        $user = User::create(
            [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email
        ]
        );
        Wallet::create(
            [
            'balance' => 0,
            'user_id' => $user->id
        ]
        );

        $payload = [
        'first_name' => $this->faker->firstName,
        'last_name'  => $this->faker->lastName,
        'email'      => $this->faker->email
    ];

        $this->json('put', "api/user/$user->id", $payload)
         ->assertStatus(Response::HTTP_OK)
         ->assertExactJson(
             [
                 'data' => [
                     'id'         => $user->id,
                     'first_name' => $payload['first_name'],
                     'last_name'  => $payload['last_name'],
                     'email'      => $payload['email'],
                     'created_at' => (string)$user->created_at,
                     'wallet'     => [
                         'id'      => $user->wallet->id,
                         'balance' => $user->wallet->balance
                     ]
                 ]
             ]
         );
    }


    public function testGetInvestmentsForUser()
    {
        $user = User::create(
            [
                             'first_name' => $this->faker->firstName,
                             'last_name'  => $this->faker->lastName,
                             'email'      => $this->faker->email
                         ]
        );

        $strategy = Strategy::create(
            Strategy::factory()->create()->getAttributes()
        );
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

        $this->json('get', "api/user/$user->id/investments")
         ->assertStatus(Response::HTTP_OK)
         ->assertJson(
             [
                 'data' => [
                     [
                         'id'          => $investment->id,
                         'user_id'     => $investment->user->id,
                         'strategy_id' => $investment->strategy->id,
                         'successful'  => (bool)$investment->successful,
                         'amount'      => $investment->amount,
                         'returns'     => $investment->returns,
                         'created_at'  => (string)$investment->created_at,
                     ]
                 ]
             ]
         );
    }


    public function testShowForMissingUser()
    {
        $this->json('get', "api/user/0")
         ->assertStatus(Response::HTTP_NOT_FOUND)
         ->assertJsonStructure(['error']);
    }

    public function testUpdateForMissingUser()
    {
        $payload = [
        'first_name' => $this->faker->firstName,
        'last_name'  => $this->faker->lastName,
        'email'      => $this->faker->email
    ];

        $this->json('put', 'api/user/0', $payload)
         ->assertStatus(Response::HTTP_NOT_FOUND)
         ->assertJsonStructure(['error']);
    }

    public function testDestroyForMissingUser()
    {
        $this->json('delete', 'api/user/0')
         ->assertStatus(Response::HTTP_NOT_FOUND)
         ->assertJsonStructure(['error']);
    }


    public function testStoreWithMissingData()
    {
        $payload = [
        'first_name' => $this->faker->firstName,
        'last_name'  => $this->faker->lastName
        //email address is missing
    ];
        $this->json('post', 'api/user', $payload)
         ->assertStatus(Response::HTTP_BAD_REQUEST)
         ->assertJsonStructure(['error']);
    }


    public function testStoredUserHasEmptyWallet()
    {
        $payload = [
        'first_name' => $this->faker->firstName,
        'last_name'  => $this->faker->lastName,
        'email'      => $this->faker->email
    ];

        $apiResponse = $this
        ->json('post', 'api/user', $payload)
        ->getContent();

        $userData = json_decode($apiResponse, true)['data'];
        $walletDetails = $userData['wallet'];

        $this->assertEquals(0, $walletDetails['balance']);
    }
}