<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestmentResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        return $this->successResponse(
            UserResource::collection(User::all())
        );
    }

    public function store(Request $request)
    {

        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');

        if (is_null($firstName) || is_null($lastName) || is_null($email)) {
            return $this->errorResponse(
                'First Name, Last Name and Email are required',
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = User::create(
            [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $email
            ]
        );

        Wallet::create(
            [
                'balance' => 0,
                'user_id' => $user->id
            ]
        );

        return $this->successResponse(
            new UserResource($user),
            true
        );
    }

    public function show(User $user)
    {
        return $this->successResponse(
            new UserResource($user)
        );
    }

    public function update(Request $request, User $user)
    {
        $user->update(
            $request->only(
                [
                    'first_name',
                    'last_name',
                    'email'
                ]
            )
        );

        return $this->successResponse(
            new UserResource($user)
        );
    }

    public function investments(User $user)
    {
        $userInvestments = $user->investments;

        return $this->successResponse(
            InvestmentResource::collection($userInvestments)
        );
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $this->deleteResponse();
    }
}