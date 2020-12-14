<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successResponse(JsonResource $data, $resourceCreated = false) {

        return response()
            ->json(
                [
                    'data' => $data
                ],
                $resourceCreated ?
                    Response::HTTP_CREATED :
                    Response::HTTP_OK
            );
    }

    protected function errorResponse(string $errorMessage, int $responseCode) {

        return response()
            ->json(
                ['error' => $errorMessage],
                $responseCode
            );
    }

    protected function deleteResponse() {

        return response()
            ->json(
                null,
                Response::HTTP_NO_CONTENT
            );
    }
}
