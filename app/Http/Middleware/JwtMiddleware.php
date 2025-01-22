<?php

namespace App\Http\Middleware;

use App\Providers\MessagesResponse;
use Closure;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            //code...
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) throw new HttpResponseException(MessagesResponse::notLogged());

            $request->merge(['user' => $user]);
            return $next($request);
        } catch (\Throwable $th) {
            throw new HttpResponseException(MessagesResponse::sessionExpired());
        }
    }
}
