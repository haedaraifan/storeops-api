<?php

namespace App\Http\Middleware;

use App\Helpers\ExceptionResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if($user->role->name == "Admin") {
            return $next($request);
        }

        if($user->role->name != $role) {
            ExceptionResponseHelper::throwForbiddenError("Forbidden. You don't have the required role to access this resource.");
        }

        return $next($request);
    }
}
