<?php

namespace App\Http\Middleware;

use App\Models\Authentication;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header("AUTHORIZATION");
        $authenticate = true;

        if(!$token) {
            $authenticate = false;
        }

        $auth = Authentication::whereToken($token)->first();

        if(!$auth) {
            $authenticate = false;
        }

        if($authenticate) {
            Auth::login($auth->user);
            return $next($request);
        } else {
            return response()->json([
                "error" => "Unauthorized."
            ])->setStatusCode(401);
        }
    }
}
