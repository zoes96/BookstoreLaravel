<?php

namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Exception;

class AuthJWT {

    public function handle($request, Closure $next)  {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['user not found'], 404);
            }
         }
        catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'invalid token']);
            }
            else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'token expired']);
            }
            else {
                return response()->json(['error' => 'authentication error']);
            }
        }
        return $next($request);
    }


}