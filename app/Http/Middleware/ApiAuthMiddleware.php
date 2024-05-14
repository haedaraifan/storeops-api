<?php

namespace App\Http\Middleware;

use App\Helpers\ExceptionResponseHelper;
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

        if(!$token || !$auth = Authentication::whereToken($token)->first()) {
            ExceptionResponseHelper::throwAuthenticationError("Unauthorized.");
        }

        if($auth->isExpired()) {
            ExceptionResponseHelper::throwAuthenticationError("Token expired.");
        }

        Auth::login($auth->user);
        return $next($request);
    }
}
