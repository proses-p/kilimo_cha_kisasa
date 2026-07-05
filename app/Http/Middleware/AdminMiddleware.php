<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $isAdmin = $user && is_string($user->role) && strtolower($user->role) === 'admin';

        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admins only.'
            ], 403);
        }

        return $next($request);
    }
}
