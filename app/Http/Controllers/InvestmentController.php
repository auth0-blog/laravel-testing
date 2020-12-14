<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use App\Models\Strategy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvestmentController extends Controller {

    public function index() {

        return $this->successResponse(
            InvestmentResource::collection(Investment::all())
        );

    }

    public function store(Request $request) {

        $userId = $request->input('user_id');
        $strategyId = $request->input('strategy_id');
        $amount = $request->input('amount');

        if (is_null($userId) || is_null($strategyId) || is_null($amount)) {
            return $this->errorResponse(
                'User ID, Strategy ID and Amount are required',
                Response::HTTP_BAD_REQUEST
            );
        }

        $strategy = Strategy::findOrFail($strategyId);
        $user = User::findOrFail($userId);

        $investment = [
            'user_id'     => $user->id,
            'strategy_id' => $strategy->id,
            'amount'      => $amount
        ];

        $successful = (bool)random_int(0, 1);
        $investment['successful'] = $successful;

        $multiplier = $successful ?
            $strategy->yield :
            $strategy->relief;
        $investment['returns'] = $amount * $multiplier;
        $investment = Investment::create($investment);

        return $this->successResponse(
            new InvestmentResource($investment),
            true
        );
    }

    public function show(Investment $investment) {

        return $this->successResponse(
            new InvestmentResource($investment)
        );

    }

    public function update() {

        return $this->errorResponse(
            'You can\'t update an investment',
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function destroy() {

        return $this->errorResponse(
            'You can\'t delete an investment',
            Response::HTTP_UNAUTHORIZED
        );
    }
}
