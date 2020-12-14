<?php

use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get(
    '/user',
    function (Request $request) {

        return $request->user();
    }
);
Route::get(
    'user/{user}/investments',
    [
        UserController::class,
        'investments'
    ]
);
Route::apiResource('strategy', StrategyController::class)
    ->middleware('jwt');
Route::apiResource('user', UserController::class);
Route::apiResource('investment', InvestmentController::class);
