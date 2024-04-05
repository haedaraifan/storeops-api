<?php

namespace App\Http\Middleware;

use App\Models\User;
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

        $user = User::join("authentications", "users.id", '=', "authentications.user_id")
            ->where("authentications.token", $token)
            ->select("users.*")
            ->first();

        if(!$user) {
            $authenticate = false;
        } else {
            Auth::login($user);
        }

        if($authenticate) {
            return $next($request);
        } else {
            return response()->json([
                "error" => "Unauthorized."
            ])->setStatusCode(401);
        }
    }
}
