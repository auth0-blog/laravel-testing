<?php

namespace App\Http\Middleware;

use App;
use Auth0\Login\Contract\Auth0UserRepository;
use Auth0\SDK\Exception\CoreException;
use Auth0\SDK\Exception\InvalidTokenException;
use Closure;
use Illuminate\Http\Response;

class CheckJWT {

    protected Auth0UserRepository $userRepository;

    public function __construct(Auth0UserRepository $userRepository) {

        $this->userRepository = $userRepository;
    }

    public function handle($request, Closure $next) {

        $auth0 = App::make('auth0');

        $accessToken = $request->bearerToken();
        if ($accessToken) {
            try {
                $tokenInfo = $auth0->decodeJWT($accessToken);
                $user = $this->userRepository->getUserByDecodedJWT($tokenInfo);
                if (!$user) {
                    return response()->json(
                        ["message" => "Unauthorized user"],
                        Response::HTTP_UNAUTHORIZED
                    );
                }

            }
            catch (InvalidTokenException $e) {
                return response()->json(
                    ["message" => $e->getMessage()],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            catch (CoreException $e) {
                return response()->json(
                    ["message" => $e->getMessage()],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            return $next($request);
        } else {
            return response()->json(
                ["message" => "No authorization token was found"],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}