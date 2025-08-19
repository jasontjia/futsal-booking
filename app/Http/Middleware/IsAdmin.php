<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Lolos jika is_admin = 1 atau role = 'admin'
            if (
                (isset($user->is_admin) && $user->is_admin == 1) ||
                (isset($user->role) && $user->role === 'admin')
            ) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}