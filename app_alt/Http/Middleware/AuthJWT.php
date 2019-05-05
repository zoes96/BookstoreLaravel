<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 29.03.2019
 * Time: 09:52
 */

namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Exception;

class AuthJWT {
    public function handle($request, Closure $next) { // mit next rufen wir im Laravel Framework das auf, was als nächstes passiert
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user not found'], 404);
            }
        }
        catch (Exception $e) {
            // Token konnte nicht auf User gemappt werden

            // Token nicht gültig
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'invalid token']);
            }

            // Token expired -> Session nach 20 Minuten abgelaufen
            else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'token expired']);
            }

            // genereller Authentification error
            else {
                return response()->json(['error' => 'authentification error']);
            }
        }
        return next($request); // wird nur aufgerufen, wenn Token valide ist
    }
}