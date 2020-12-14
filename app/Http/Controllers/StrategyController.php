<?php

namespace App\Http\Controllers;

use App\Http\Resources\StrategyResource;
use App\Models\Strategy;
use Illuminate\Http\Request;

class StrategyController extends Controller {

    public function index() {

        return $this->successResponse(
            StrategyResource::collection(Strategy::all())
        );

    }

    public function store(Request $request) {

        $strategy = Strategy::create($request->all());

        return $this->successResponse(
            new StrategyResource($strategy),
            true
        );
    }

    public function show(Strategy $strategy) {

        return $this->successResponse(
            new StrategyResource($strategy)
        );

    }

    public function update(Request $request, Strategy $strategy) {

        $strategy->update(
            $request->only(
                [
                    'type',
                    'tenure',
                    'yield',
                    'relief'
                ]
            )
        );

        return $this->successResponse(
            new StrategyResource($strategy)
        );

    }

    public function destroy(Strategy $strategy) {

        $strategy->delete();

        return $this->deleteResponse();
    }
}
